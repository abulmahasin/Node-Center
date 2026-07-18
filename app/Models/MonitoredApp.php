<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class MonitoredApp extends Model {
    use HasApiTokens;

    protected $guarded = ['id'];
    protected $casts = [
        'last_ping_at' => 'datetime',
        'last_active_ping_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function incidents() {
        return $this->hasMany(AppIncident::class, 'monitored_app_id');
    }

    public function metrics() {
        return $this->hasMany(AppMetric::class);
    }

    public function executePing()
    {
        $previousStatus = $this->ping_status;

        try {
            // SSL Check
            $sslExpiresAt = null;
            $sslIssuer = null;
            if (str_starts_with($this->url, 'https://')) {
                try {
                    $parsedUrl = parse_url($this->url);
                    $host = $parsedUrl['host'] ?? null;
                    if ($host) {
                        $streamContext = stream_context_create(['ssl' => ['capture_peer_cert' => true]]);
                        $client = @stream_socket_client("ssl://{$host}:443", $errno, $errstr, 2, STREAM_CLIENT_CONNECT, $streamContext);
                        if ($client) {
                            $params = stream_context_get_params($client);
                            if (isset($params['options']['ssl']['peer_certificate'])) {
                                $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
                                if ($cert && isset($cert['validTo_time_t'])) {
                                    $sslExpiresAt = date('Y-m-d H:i:s', $cert['validTo_time_t']);
                                    $sslIssuer = $cert['issuer']['O'] ?? ($cert['issuer']['CN'] ?? 'Unknown');
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {}
            }

            $start = microtime(true);
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get($this->url);
            $timeMs = (int) round((microtime(true) - $start) * 1000);
            
            if ($response->successful() || $response->redirect()) {
                $this->update([
                    'ping_status' => 'up',
                    'ping_response_time' => $timeMs,
                    'ping_error' => null,
                    'last_active_ping_at' => now(),
                    'ssl_expires_at' => $sslExpiresAt,
                    'ssl_issuer' => $sslIssuer,
                ]);
                $this->incidents()->whereNull('resolved_at')->update(['resolved_at' => now()]);

                // Send RECOVERY alert if previously down
                if ($previousStatus === 'down') {
                    $this->sendRecoveryAlert($timeMs);
                }
            } else {
                $errorMsg = 'HTTP ' . $response->status();
                $this->update([
                    'ping_status' => 'down',
                    'ping_response_time' => $timeMs,
                    'ping_error' => $errorMsg,
                    'last_active_ping_at' => now(),
                    'ssl_expires_at' => $sslExpiresAt,
                    'ssl_issuer' => $sslIssuer,
                ]);
                if (!$this->incidents()->whereNull('resolved_at')->exists()) {
                    $this->incidents()->create(['status' => 'offline', 'started_at' => now(), 'error_message' => $errorMsg]);
                }

                // Send DOWN alert if previously up
                if ($previousStatus !== 'down') {
                    $this->sendDownAlert($errorMsg);
                }
            }
        } catch (\Exception $e) {
            $errorMsg = 'Connection Failed: ' . $e->getMessage();
            $this->update([
                'ping_status' => 'down',
                'ping_response_time' => null,
                'ping_error' => $errorMsg,
                'last_active_ping_at' => now(),
            ]);
            if (!$this->incidents()->whereNull('resolved_at')->exists()) {
                $this->incidents()->create(['status' => 'offline', 'started_at' => now(), 'error_message' => $errorMsg]);
            }

            // Send DOWN alert if previously up
            if ($previousStatus !== 'down') {
                $this->sendDownAlert($errorMsg);
            }
        }
    }

    /**
     * Send a Telegram/Email alert when app goes DOWN.
     */
    protected function sendDownAlert(string $errorMsg)
    {
        if (Setting::get('alerts_enabled', 'true') !== 'true') return;

        $message = "🚨 *ALERT: APPLICATION DOWN*\n\n"
            . "📛 *App:* {$this->name}\n"
            . "🌐 *URL:* {$this->url}\n"
            . "❌ *Error:* {$errorMsg}\n"
            . "🕐 *Time:* " . now()->format('Y-m-d H:i:s') . "\n\n"
            . "⚠️ Immediate attention required!";

        $this->sendTelegramMessage($message, 'down');
        $this->sendEmailAlert("🚨 DOWN: {$this->name}", $message, 'down');
    }

    /**
     * Send a Telegram/Email alert when app RECOVERS from downtime.
     */
    protected function sendRecoveryAlert(int $responseTime)
    {
        if (Setting::get('alerts_enabled', 'true') !== 'true') return;

        $message = "✅ *RECOVERED: APPLICATION BACK ONLINE*\n\n"
            . "📛 *App:* {$this->name}\n"
            . "🌐 *URL:* {$this->url}\n"
            . "⚡ *Response:* {$responseTime}ms\n"
            . "🕐 *Time:* " . now()->format('Y-m-d H:i:s') . "\n\n"
            . "🎉 Service has been restored.";

        $this->sendTelegramMessage($message, 'recovery');
        $this->sendEmailAlert("✅ RECOVERED: {$this->name}", $message, 'recovery');
    }

    /**
     * Send message via Telegram Bot API.
     * Uses DB settings first, falls back to .env, then per-app chat_id.
     */
    protected function sendTelegramMessage(string $message, string $type = 'down')
    {
        $botToken = Setting::get('telegram_bot_token');
        $chatId = $this->telegram_chat_id ?: Setting::get('telegram_default_chat_id');

        if (!$botToken || !$chatId) return;

        try {
            $response = \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

            AlertLog::create([
                'monitored_app_id' => $this->id,
                'channel' => 'telegram',
                'type' => $type,
                'message' => $message,
                'status' => $response->successful() && $response->json('ok') ? 'sent' : 'failed',
                'error' => $response->successful() ? null : ($response->json('description') ?? 'Unknown'),
            ]);
        } catch (\Exception $e) {
            AlertLog::create([
                'monitored_app_id' => $this->id,
                'channel' => 'telegram',
                'type' => $type,
                'message' => $message,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
            \Illuminate\Support\Facades\Log::warning("Telegram alert failed for {$this->name}: " . $e->getMessage());
        }
    }

    /**
     * Send alert via Email (uses Laravel Mail).
     */
    protected function sendEmailAlert(string $subject, string $body, string $type = 'down')
    {
        $email = $this->alert_email;

        if (!$email) return;

        try {
            \Illuminate\Support\Facades\Mail::raw(strip_tags(str_replace('\n', "\n", $body)), function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            AlertLog::create([
                'monitored_app_id' => $this->id,
                'channel' => 'email',
                'type' => $type,
                'message' => $body,
                'status' => 'sent',
            ]);
        } catch (\Exception $e) {
            AlertLog::create([
                'monitored_app_id' => $this->id,
                'channel' => 'email',
                'type' => $type,
                'message' => $body,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
            \Illuminate\Support\Facades\Log::warning("Email alert failed for {$this->name}: " . $e->getMessage());
        }
    }
}
