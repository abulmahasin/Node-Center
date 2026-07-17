<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PingAppsCommand extends Command
{
    protected $signature = 'dashboard:ping';

    protected $description = 'Ping all monitored applications to check their active status';

    public function handle()
    {
        $apps = \App\Models\MonitoredApp::all();
        
        foreach ($apps as $app) {
            try {
                // Ping the root URL, wait max 5 seconds
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get($app->url);
                
                if ($response->successful()) {
                    $app->update([
                        'status' => 'online',
                        'last_ping_at' => now(),
                    ]);
                    $this->info("Ping SUCCESS: {$app->name}");
                } else {
                    $app->update(['status' => 'offline']);
                    $this->error("Ping FAILED (Status {$response->status()}): {$app->name}");
                }
            } catch (\Exception $e) {
                $app->update(['status' => 'offline']);
                $this->error("Ping EXCEPTION: {$app->name} - " . $e->getMessage());
            }
        }
    }
}
