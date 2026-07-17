<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MonitoredApp;
use Illuminate\Support\Facades\Http;

class MonitorAppsCommand extends Command
{
    protected $signature = 'app:monitor-apps';
    protected $description = 'PULL metrics from all registered applications';

    public function handle()
    {
        $apps = MonitoredApp::all();
        $this->info("Found {$apps->count()} apps to monitor.");

        foreach ($apps as $app) {
            $this->info("Pinging {$app->name}...");
            $startTime = microtime(true);
            
            try {
                $response = Http::withToken($app->api_key)
                    ->timeout(5)
                    ->get($app->url);
                
                $latencyMs = round((microtime(true) - $startTime) * 1000);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    $app->update(['status' => $data['status'] ?? 'unknown']);
                    
                    $metrics = $data['metrics'] ?? [];
                    $deps = $data['dependencies'] ?? [];
                    
                    $app->metrics()->create([
                        'cpu_usage_percent' => $metrics['cpu_usage_percent'] ?? null,
                        'memory_used_mb' => $metrics['memory_used_mb'] ?? null,
                        'memory_total_mb' => $metrics['memory_total_mb'] ?? null,
                        'db_status' => $deps['database']['status'] ?? null,
                        'latency_ms' => $latencyMs,
                    ]);
                    
                    $this->info("Success: {$app->name} is {$app->status}");
                } else {
                    $app->update(['status' => 'error']);
                    $this->error("Failed to ping {$app->name} - HTTP {$response->status()}");
                }
            } catch (\Exception $e) {
                $app->update(['status' => 'offline']);
                $this->error("Error pinging {$app->name}: " . $e->getMessage());
            }
        }
    }
}
