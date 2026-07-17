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
            $app->executePing();
            if ($app->ping_status === 'up') {
                $this->info("Ping SUCCESS: {$app->name} ({$app->ping_response_time}ms)");
            } else {
                $this->error("Ping FAILED ({$app->ping_error}): {$app->name}");
            }
        }
        
        $this->info("Ping cycle completed.");
    }
}
