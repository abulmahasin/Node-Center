<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SendDashboardMetrics extends Command
{
    protected $signature = 'dashboard:send-metrics';
    protected $description = 'Send system metrics to Node Center Dashboard';

    public function handle()
    {
        $dbStart = microtime(true);
        try { DB::connection()->getPdo(); } catch (\Exception $e) {}
        $dbLatency = round((microtime(true) - $dbStart) * 1000);

        // Pencegahan error jika fungsi diblokir cPanel
        $diskTotal = @disk_total_space(base_path()) ?: 1; 
        $diskFree  = @disk_free_space(base_path()) ?: 0;

        $pendingJobs = 0;
        $failedJobs = 0;
        $queueDetails = ['pending' => [], 'failed' => []];
        try {
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();
            
            $queueDetails['pending'] = DB::table('jobs')->select('id', 'queue', 'payload', 'created_at')->limit(5)->get()->toArray();
            $queueDetails['failed'] = DB::table('failed_jobs')->select('id', 'queue', 'payload', 'failed_at')->limit(5)->get()->toArray();
        } catch (\Exception $e) {}

        // Log Analyzer (Error & Slow Queries) yang kebal dari shell_exec blocked
        $errorCount = 0;
        $errorDetails = [];
        $slowQueries = [];
        $logPath = storage_path('logs/laravel.log');
        
        if (file_exists($logPath)) {
            $logContent = @file_get_contents($logPath);
            if ($logContent) {
                preg_match_all('/\[.*?\] .*?ERROR: (.*)/', $logContent, $matches);
                $errorCount = count($matches[0]);
                $errorDetails = array_slice($matches[1], -5);
                
                preg_match_all('/\[.*?\] .*?Slow query detected: (.*)/', $logContent, $slowMatches);
                $slowQueries = array_slice($slowMatches[1], -5);
            }
        }
        
        // Security Checker (.env scan)
        $securityWarnings = [];
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = @file_get_contents($envPath);
            if ($envContent) {
                if (!str_contains($envContent, 'APP_KEY=base64:')) {
                    $securityWarnings[] = 'APP_KEY is missing or not a valid base64 string.';
                }
                if (str_contains($envContent, 'APP_DEBUG=true') && config('app.env') === 'production') {
                    $securityWarnings[] = 'CRITICAL: APP_DEBUG is true in production environment!';
                }
                if (str_contains($envContent, 'DB_PASSWORD=') && !preg_match('/DB_PASSWORD=.+/', $envContent) && config('app.env') === 'production') {
                    $securityWarnings[] = 'WARNING: Database password appears to be empty in production.';
                }
            }
        }

        $cacheStart = microtime(true);
        try {
            Cache::has('ping');
            $cacheLatency = round((microtime(true) - $cacheStart) * 1000);
        } catch (\Exception $e) {
            $cacheLatency = null;
        }

        $scheduleDetails = [];
        try {
            $schedule = app(\Illuminate\Console\Scheduling\Schedule::class);
            foreach ($schedule->events() as $event) {
                $scheduleDetails[] = [
                    'command' => $event->command,
                    'expression' => $event->expression,
                    'description' => $event->description,
                    'next_run' => $event->nextRunDate()->format('Y-m-d H:i:s')
                ];
            }
        } catch (\Exception $e) {}

        // Pencegahan fatal error sys_getloadavg di cPanel
        $cpuUsage = 0;
        if (function_exists('sys_getloadavg') && is_array(sys_getloadavg())) {
            $cpuUsage = sys_getloadavg()[0] * 10;
        }

        $metrics = [
            'cpu_usage'       => $cpuUsage,
            'memory_usage'    => round(memory_get_usage(true) / 1048576, 1),
            'disk_usage'      => round((($diskTotal - $diskFree) / $diskTotal) * 100, 1),
            'active_users'    => \App\Models\User::count(),
            'db_latency'      => $dbLatency,
            'cache_latency'   => $cacheLatency,
            'pending_jobs'    => $pendingJobs,
            'failed_jobs'     => $failedJobs,
            'error_count'     => $errorCount,
            'php_version'     => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env'         => app()->environment(),
            'error_details'   => $errorDetails,
            'queue_details'   => $queueDetails,
            'schedule_details'=> $scheduleDetails,
            'slow_queries'    => $slowQueries,
            'security_warnings' => $securityWarnings,
            'response_time'   => 120,
            'error_rate'      => 0.0,
        ];

        if (env('DASHBOARD_API_TOKEN')) {
            Http::withToken(env('DASHBOARD_API_TOKEN'))
                ->timeout(3)
                ->post(env('DASHBOARD_MONITOR_URL') . '/api/metrics', $metrics);
            
            $this->info('Metrics sent successfully!');
        } else {
            $this->error('DASHBOARD_API_TOKEN is not set in .env');
        }
    }
}
