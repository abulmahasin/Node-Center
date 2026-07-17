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
                    
                    // Resolve any open incident
                    $app->incidents()->whereNull('resolved_at')->update(['resolved_at' => now()]);
                    
                } else {
                    $errorMsg = 'HTTP ' . $response->status();
                    $app->update([
                        'ping_status' => 'down',
                        'ping_response_time' => $timeMs,
                        'ping_error' => $errorMsg,
                        'last_active_ping_at' => now(),
                    ]);
                    $this->error("Ping FAILED ({$errorMsg}): {$app->name}");
                    
                    // Create an incident if one doesn't exist
                    if (!$app->incidents()->whereNull('resolved_at')->exists()) {
                        $app->incidents()->create([
                            'status' => 'offline',
                            'started_at' => now(),
                            'error_message' => $errorMsg,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                $errorMsg = 'Connection Failed: ' . $e->getMessage();
                $app->update([
                    'ping_status' => 'down',
                    'ping_response_time' => null,
                    'ping_error' => $errorMsg,
                    'last_active_ping_at' => now(),
                ]);
                $this->error("Ping EXCEPTION: {$app->name} - Connection Failed");
                
                // Create an incident if one doesn't exist
                if (!$app->incidents()->whereNull('resolved_at')->exists()) {
                    $app->incidents()->create([
                        'status' => 'offline',
                        'started_at' => now(),
                        'error_message' => $errorMsg,
                    ]);
                }
            }
        }
        
        $this->info("Ping cycle completed.");
    }
}
