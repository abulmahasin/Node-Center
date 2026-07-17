<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MonitoredApp;
use Illuminate\Support\Facades\Http;

class PingAppsCommand extends Command
{
    protected $signature = 'apps:ping';

    protected $description = 'Ping all monitored applications to check their active uptime status and response time';

    public function handle()
    {
        $apps = MonitoredApp::all();
        $this->info("Starting ping for {$apps->count()} apps...");
        
        foreach ($apps as $app) {
            try {
                // Measure time
                $start = microtime(true);
                $response = Http::timeout(5)->get($app->url);
                $timeMs = (int) round((microtime(true) - $start) * 1000);
                
                if ($response->successful() || $response->redirect()) {
                    $app->update([
                        'ping_status' => 'up',
                        'ping_response_time' => $timeMs,
                        'ping_error' => null,
                        'last_active_ping_at' => now(),
                    ]);
                    $this->info("Ping SUCCESS: {$app->name} ({$timeMs}ms)");
                } else {
                    $app->update([
                        'ping_status' => 'down',
                        'ping_response_time' => $timeMs,
                        'ping_error' => 'HTTP ' . $response->status(),
                        'last_active_ping_at' => now(),
                    ]);
                    $this->error("Ping FAILED (HTTP {$response->status()}): {$app->name}");
                }
            } catch (\Exception $e) {
                $app->update([
                    'ping_status' => 'down',
                    'ping_response_time' => null,
                    'ping_error' => 'Connection Failed: ' . $e->getMessage(),
                    'last_active_ping_at' => now(),
                ]);
                $this->error("Ping EXCEPTION: {$app->name} - Connection Failed");
            }
        }
        
        $this->info("Ping cycle completed.");
    }
}
