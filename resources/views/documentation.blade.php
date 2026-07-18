<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.2rem; font-weight: 700;">📖 API Documentation</h2>
    </x-slot>

    <div style="max-width: 720px;">

        <!-- Quick Start -->
        <div class="neo-card" style="margin-bottom: 1.25rem; border-left: 6px solid #6C63FF;">
            <h3 style="font-weight: 700; font-size: 1.05rem; margin-bottom: 0.75rem;">🚀 Quick Start — 3 Steps</h3>
            <p style="font-size: 0.8rem; color: #555; line-height: 1.6; margin-bottom: 1.25rem;">
                Connect your applications to this monitoring dashboard in under 5 minutes.
            </p>

            <div class="neo-step">
                <div class="neo-step-num">1</div>
                <div>
                    <h4>Register Your App</h4>
                    <p>Go to <a href="{{ route('apps.index') }}" style="color: #6C63FF; font-weight: 700; text-decoration: none;">My Apps</a> → click <strong>"+ New App"</strong> → enter name & URL.</p>
                </div>
            </div>
            <div class="neo-step">
                <div class="neo-step-num">2</div>
                <div>
                    <h4>Generate an API Token</h4>
                    <p>Click the <strong style="color: #b45309;">🔑 Token</strong> button next to your app. A modal will ask for confirmation, then your token is shown <strong>once</strong>. Copy it!</p>
                </div>
            </div>
            <div class="neo-step">
                <div class="neo-step-num">3</div>
                <div>
                    <h4>Send Metrics via HTTP POST</h4>
                    <p>Use the code examples below to send data from your app on a regular schedule (e.g., every minute).</p>
                </div>
            </div>
        </div>

        <!-- API Reference -->
        <div class="neo-card" style="margin-bottom: 1.25rem; border-left: 6px solid #22c55e;">
            <h3 style="font-weight: 700; font-size: 1.05rem; margin-bottom: 1rem;">📡 API Reference</h3>

            <div style="margin-bottom: 1rem;">
                <span class="neo-label">Endpoint</span>
                <div style="background: var(--mint); border: 2px solid var(--border); border-radius: 0.5rem; padding: 0.65rem 0.85rem; font-family: monospace; font-size: 0.8rem; font-weight: 700; box-shadow: 2px 2px 0 var(--border);">
                    POST {{ url('/api/metrics') }}
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <span class="neo-label">Headers</span>
                <div class="neo-code">
<span class="var">Accept</span>: <span class="str">application/json</span>
<span class="var">Content-Type</span>: <span class="str">application/json</span>
<span class="var">Authorization</span>: <span class="str">Bearer</span> <span class="kw">{YOUR_API_TOKEN}</span></div>
            </div>

            <div>
                <span class="neo-label">Request Body</span>
                <div class="neo-code">
{
    <span class="str">"cpu_usage"</span>: <span class="var">45.2</span>,       <span class="cmt">// float — CPU %</span>
    <span class="str">"memory_usage"</span>: <span class="var">60.1</span>,    <span class="cmt">// float — Memory %</span>
    <span class="str">"disk_usage"</span>: <span class="var">72.5</span>,      <span class="cmt">// float — Disk Used %</span>
    <span class="str">"active_users"</span>: <span class="var">125</span>,     <span class="cmt">// integer — Active users</span>
    <span class="str">"db_latency"</span>: <span class="var">45</span>,        <span class="cmt">// integer — DB connection latency (ms)</span>
    <span class="str">"cache_latency"</span>: <span class="var">5</span>,      <span class="cmt">// integer — Cache connection latency (ms)</span>
    <span class="str">"pending_jobs"</span>: <span class="var">12</span>,      <span class="cmt">// integer — Queue pending jobs</span>
    <span class="str">"failed_jobs"</span>: <span class="var">0</span>,        <span class="cmt">// integer — Queue failed jobs</span>
    <span class="str">"error_count"</span>: <span class="var">2</span>,        <span class="cmt">// integer — Recent errors in log</span>
    <span class="str">"php_version"</span>: <span class="str">"8.4.1"</span>,  <span class="cmt">// string — PHP Version</span>
    <span class="str">"laravel_version"</span>: <span class="str">"11.x"</span>,<span class="cmt">// string — Laravel Version</span>
    <span class="str">"app_env"</span>: <span class="str">"production"</span>, <span class="cmt">// string — App Environment</span>
    <span class="str">"response_time"</span>: <span class="var">250</span>,    <span class="cmt">// float — Avg latency (ms)</span>
    <span class="str">"error_rate"</span>: <span class="var">0.5</span>        <span class="cmt">// float — HTTP Error %</span>
}</div>
            </div>
        </div>

        <!-- Laravel Implementation (The Simple Way) -->
        <div class="neo-card" style="margin-bottom: 1.25rem; border-left: 6px solid #d97706;">
            <h3 style="font-weight: 700; font-size: 1.05rem; margin-bottom: 0.5rem;">🛠️ Laravel Integration (The Clean Way)</h3>
            <p style="font-size: 0.8rem; color: #555; margin-bottom: 1rem;">
                Instead of cluttering your code, simply create a dedicated Console Command. Follow these two easy steps:
            </p>
            
            @php
                $envExample = <<<'ENV'
DASHBOARD_API_TOKEN=your-api-token-here
DASHBOARD_MONITOR_URL=https://your-dashboard-domain.com
ENV;

                $commandExample = <<<'PHP'
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SendDashboardMetrics extends Command
{
    protected $signature = 'dashboard:send-metrics';
    protected $description = 'Send application metrics to the dashboard';

    public function handle(): int
    {
        $dbStart = microtime(true);
        try {
            DB::connection()->getPdo();
            $dbLatency = round((microtime(true) - $dbStart) * 1000);
        } catch (\Throwable $e) {
            $dbLatency = 0;
        }

        $diskTotal = disk_total_space(base_path());
        $diskFree = disk_free_space(base_path());

        $pendingJobs = 0;
        $failedJobs = 0;
        try {
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();
        } catch (\Throwable $e) {
            // ignore when tables are unavailable
        }

        $errorCount = 0;
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $recentLogs = shell_exec('tail -n 300 ' . escapeshellarg($logFile));
            $errorCount = substr_count($recentLogs ?? '', 'local.ERROR');
        }

        $cacheStart = microtime(true);
        try {
            Cache::has('ping');
            $cacheLatency = round((microtime(true) - $cacheStart) * 1000);
        } catch (\Throwable $e) {
            $cacheLatency = 0;
        }

        $metrics = [
            'cpu_usage' => round(sys_getloadavg()[0] * 10, 1),
            'memory_usage' => round(memory_get_usage(true) / 1048576, 1),
            'disk_usage' => round((($diskTotal - $diskFree) / $diskTotal) * 100, 1),
            'active_users' => \App\Models\User::count(),
            'db_latency' => $dbLatency,
            'cache_latency' => $cacheLatency,
            'pending_jobs' => $pendingJobs,
            'failed_jobs' => $failedJobs,
            'error_count' => $errorCount,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => app()->environment(),
            'response_time' => 120,
            'error_rate' => 0.0,
        ];

        if (env('DASHBOARD_API_TOKEN') && env('DASHBOARD_MONITOR_URL')) {
            Http::withToken(env('DASHBOARD_API_TOKEN'))
                ->timeout(3)
                ->post(rtrim(env('DASHBOARD_MONITOR_URL'), '/') . '/api/metrics', $metrics);
        }

        return self::SUCCESS;
    }
}
PHP;
            @endphp

            <h4 style="font-weight: 700; font-size: 0.9rem; margin-bottom: 0.25rem;">1. Configure .env</h4>
            <p style="font-size: 0.8rem; color: #555; margin-bottom: 0.5rem;">Add these values to your application environment so the command can send metrics to the correct endpoint.</p>
            <pre class="neo-code" style="margin-bottom: 1rem; white-space: pre-wrap; overflow-x: auto; font-family: 'SFMono-Regular', Consolas, Monaco, monospace;">{{ $envExample }}</pre>

            <h4 style="font-weight: 700; font-size: 0.9rem; margin-bottom: 0.25rem;">2. Create the Command</h4>
            <p style="font-size: 0.8rem; color: #555; margin-bottom: 0.5rem;">Run this in your terminal to generate the file:</p>
            <pre class="neo-code" style="margin-bottom: 1rem; white-space: pre-wrap; overflow-x: auto; font-family: 'SFMono-Regular', Consolas, Monaco, monospace;">php artisan make:command SendDashboardMetrics</pre>

            <p style="font-size: 0.8rem; color: #555; margin-bottom: 0.5rem;">Then, paste this code into <code style="background: var(--butter); padding: 0.1rem 0.4rem; border-radius: 0.25rem; border: 1px solid var(--border); font-size: 0.75rem;">app/Console/Commands/SendDashboardMetrics.php</code>:</p>

            <pre class="neo-code" style="max-height: 260px; overflow-y: auto; margin-bottom: 1rem; white-space: pre-wrap; overflow-x: auto; font-family: 'SFMono-Regular', Consolas, Monaco, monospace;">{{ $commandExample }}</pre>

            <h4 style="font-weight: 700; font-size: 0.9rem; margin-bottom: 0.25rem;">3. Schedule It</h4>
            <p style="font-size: 0.8rem; color: #555; margin-bottom: 0.5rem;">Add just one line to your <code style="background: var(--butter); padding: 0.1rem 0.4rem; border-radius: 0.25rem; border: 1px solid var(--border); font-size: 0.75rem;">routes/console.php</code> (Laravel 11) or <code style="background: var(--butter); padding: 0.1rem 0.4rem; border-radius: 0.25rem; border: 1px solid var(--border); font-size: 0.75rem;">app/Console/Kernel.php</code> (Laravel 10):</p>
            <div class="neo-code">
Schedule::<span class="fn">command</span>(<span class="str">'dashboard:send-metrics'</span>)-><span class="fn">everyMinute</span>();</div>
        </div>

        <!-- cURL Example -->
        <div class="neo-card" style="border-left: 6px solid #ec4899;">
            <h3 style="font-weight: 700; font-size: 1.05rem; margin-bottom: 0.5rem;">💻 cURL Example</h3>
            <p style="font-size: 0.8rem; color: #555; margin-bottom: 1rem;">Test manually from your terminal:</p>
            <div class="neo-code">
<span class="fn">curl</span> -X <span class="kw">POST</span> {{ url('/api/metrics') }} \
  -H <span class="str">"Accept: application/json"</span> \
  -H <span class="str">"Content-Type: application/json"</span> \
  -H <span class="str">"Authorization: Bearer YOUR_TOKEN"</span> \
  -d <span class="str">'{
    "cpu_usage": 45.2,
    "memory_usage": 60.1,
    "active_users": 125,
    "response_time": 250,
    "error_rate": 0.5
  }'</span></div>
        </div>
    </div>
</x-app-layout>
