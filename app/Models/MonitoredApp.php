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
        try {
            $start = microtime(true);
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get($this->url);
            $timeMs = (int) round((microtime(true) - $start) * 1000);
            
            if ($response->successful() || $response->redirect()) {
                $this->update([
                    'ping_status' => 'up',
                    'ping_response_time' => $timeMs,
                    'ping_error' => null,
                    'last_active_ping_at' => now(),
                ]);
                $this->incidents()->whereNull('resolved_at')->update(['resolved_at' => now()]);
            } else {
                $errorMsg = 'HTTP ' . $response->status();
                $this->update([
                    'ping_status' => 'down',
                    'ping_response_time' => $timeMs,
                    'ping_error' => $errorMsg,
                    'last_active_ping_at' => now(),
                ]);
                if (!$this->incidents()->whereNull('resolved_at')->exists()) {
                    $this->incidents()->create(['status' => 'offline', 'started_at' => now(), 'error_message' => $errorMsg]);
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
        }
    }
}
