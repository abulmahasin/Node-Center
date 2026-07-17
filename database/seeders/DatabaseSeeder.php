<?php

namespace Database\Seeders;

use App\Models\MonitoredApp;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $apps = [
            [
                'name' => 'HRIS App',
                'url' => 'http://localhost/api/health',
                'api_key' => 'secret123',
                'type' => 'laravel',
                'status' => 'healthy',
            ],
            [
                'name' => 'Asrama App (SISMA-AKA)',
                'url' => 'http://localhost/api/health',
                'api_key' => 'secret123',
                'type' => 'laravel',
                'status' => 'healthy',
            ],
            [
                'name' => 'Asset Backend',
                'url' => 'http://localhost/api/health',
                'api_key' => 'secret123',
                'type' => 'nestjs',
                'status' => 'error',
            ],
            [
                'name' => 'Asset Frontend',
                'url' => 'http://localhost/api/health',
                'api_key' => 'secret123',
                'type' => 'nextjs',
                'status' => 'offline',
            ],
        ];

        foreach ($apps as $appData) {
            $app = MonitoredApp::create($appData);
            
            // Create dummy metrics
            $app->metrics()->create([
                'cpu_usage_percent' => rand(10, 60),
                'memory_used_mb' => rand(200, 800),
                'memory_total_mb' => 2048,
                'db_status' => $app->status === 'error' || $app->status === 'offline' ? 'disconnected' : 'connected',
                'latency_ms' => rand(15, 120),
            ]);
        }
    }
}
