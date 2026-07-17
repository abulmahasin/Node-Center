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
            
            <h4 style="font-weight: 700; font-size: 0.9rem; margin-bottom: 0.25rem;">1. Create the Command</h4>
            <p style="font-size: 0.8rem; color: #555; margin-bottom: 0.5rem;">Run this in your terminal to generate the file:</p>
            <div class="neo-code" style="margin-bottom: 1rem;">
<span class="kw">php</span> artisan make:command SendDashboardMetrics</div>

            <p style="font-size: 0.8rem; color: #555; margin-bottom: 0.5rem;">Then, paste this code into <code style="background: var(--butter); padding: 0.1rem 0.4rem; border-radius: 0.25rem; border: 1px solid var(--border); font-size: 0.75rem;">app/Console/Commands/SendDashboardMetrics.php</code>:</p>
            
            <div class="neo-code" style="max-height: 250px; overflow-y: auto; margin-bottom: 1rem;">
<span class="kw">&lt;?php</span>
<span class="kw">namespace</span> App\Console\Commands;

<span class="kw">use</span> Illuminate\Console\Command;
<span class="kw">use</span> Illuminate\Support\Facades\Http;
<span class="kw">use</span> Illuminate\Support\Facades\DB;
<span class="kw">use</span> Illuminate\Support\Facades\Cache;

<span class="kw">class</span> <span class="fn">SendDashboardMetrics</span> <span class="kw">extends</span> Command
{
    <span class="kw">protected</span> <span class="var">$signature</span> = <span class="str">'dashboard:send-metrics'</span>;
    <span class="kw">protected</span> <span class="var">$description</span> = <span class="str">'Send system metrics to Dashboard Monitor'</span>;

    <span class="kw">public function</span> <span class="fn">handle</span>()
    {
        <span class="var">$dbStart</span> = <span class="fn">microtime</span>(<span class="kw">true</span>);
        <span class="kw">try</span> { DB::<span class="fn">connection</span>()-><span class="fn">getPdo</span>(); } <span class="kw">catch</span> (\Exception <span class="var">$e</span>) {}
        <span class="var">$dbLatency</span> = <span class="fn">round</span>((<span class="fn">microtime</span>(<span class="kw">true</span>) - <span class="var">$dbStart</span>) * <span class="var">1000</span>);

        <span class="var">$diskTotal</span> = <span class="fn">disk_total_space</span>(<span class="fn">base_path</span>());
        <span class="var">$diskFree</span>  = <span class="fn">disk_free_space</span>(<span class="fn">base_path</span>());

        <span class="var">$pendingJobs</span> = <span class="var">0</span>;
        <span class="var">$failedJobs</span> = <span class="var">0</span>;
        <span class="kw">try</span> {
            <span class="var">$pendingJobs</span> = DB::<span class="fn">table</span>(<span class="str">'jobs'</span>)-><span class="fn">count</span>();
            <span class="var">$failedJobs</span> = DB::<span class="fn">table</span>(<span class="str">'failed_jobs'</span>)-><span class="fn">count</span>();
        } <span class="kw">catch</span> (\Exception <span class="var">$e</span>) {}

        <span class="var">$errorCount</span> = <span class="var">0</span>;
        <span class="var">$logFile</span> = <span class="fn">storage_path</span>(<span class="str">'logs/laravel.log'</span>);
        <span class="kw">if</span> (<span class="fn">file_exists</span>(<span class="var">$logFile</span>)) {
            <span class="var">$recentLogs</span> = <span class="fn">shell_exec</span>(<span class="str">'tail -n 300 '</span> . <span class="fn">escapeshellarg</span>(<span class="var">$logFile</span>));
            <span class="var">$errorCount</span> = <span class="fn">substr_count</span>(<span class="var">$recentLogs</span> ?? <span class="str">''</span>, <span class="str">'local.ERROR'</span>);
        }

        <span class="var">$cacheStart</span> = <span class="fn">microtime</span>(<span class="kw">true</span>);
        <span class="kw">try</span> {
            Cache::<span class="fn">has</span>(<span class="str">'ping'</span>);
            <span class="var">$cacheLatency</span> = <span class="fn">round</span>((<span class="fn">microtime</span>(<span class="kw">true</span>) - <span class="var">$cacheStart</span>) * <span class="var">1000</span>);
        } <span class="kw">catch</span> (\Exception <span class="var">$e</span>) {
            <span class="var">$cacheLatency</span> = <span class="kw">null</span>;
        }

        <span class="var">$metrics</span> = [
            <span class="str">'cpu_usage'</span>       => <span class="fn">sys_getloadavg</span>()[<span class="var">0</span>] * <span class="var">10</span>,
            <span class="str">'memory_usage'</span>    => <span class="fn">round</span>(<span class="fn">memory_get_usage</span>(<span class="kw">true</span>) / <span class="var">1048576</span>, <span class="var">1</span>),
            <span class="str">'disk_usage'</span>      => <span class="fn">round</span>(((<span class="var">$diskTotal</span> - <span class="var">$diskFree</span>) / <span class="var">$diskTotal</span>) * <span class="var">100</span>, <span class="var">1</span>),
            <span class="str">'active_users'</span>    => \App\Models\User::<span class="fn">count</span>(),
            <span class="str">'db_latency'</span>      => <span class="var">$dbLatency</span>,
            <span class="str">'cache_latency'</span>   => <span class="var">$cacheLatency</span>,
            <span class="str">'pending_jobs'</span>    => <span class="var">$pendingJobs</span>,
            <span class="str">'failed_jobs'</span>     => <span class="var">$failedJobs</span>,
            <span class="str">'error_count'</span>     => <span class="var">$errorCount</span>,
            <span class="str">'php_version'</span>     => <span class="kw">PHP_VERSION</span>,
            <span class="str">'laravel_version'</span> => <span class="fn">app</span>()-><span class="fn">version</span>(),
            <span class="str">'app_env'</span>         => <span class="fn">app</span>()-><span class="fn">environment</span>(),
            <span class="str">'response_time'</span>   => <span class="var">120</span>,
            <span class="str">'error_rate'</span>      => <span class="var">0.0</span>,
        ];

        <span class="kw">if</span> (<span class="fn">env</span>(<span class="str">'DASHBOARD_API_TOKEN'</span>)) {
            Http::<span class="fn">withToken</span>(<span class="fn">env</span>(<span class="str">'DASHBOARD_API_TOKEN'</span>))
                -><span class="fn">timeout</span>(<span class="var">3</span>)
                -><span class="fn">post</span>(<span class="fn">env</span>(<span class="str">'DASHBOARD_MONITOR_URL'</span>) . <span class="str">'/api/metrics'</span>, <span class="var">$metrics</span>);
        }
    }
}</div>

            <h4 style="font-weight: 700; font-size: 0.9rem; margin-bottom: 0.25rem;">2. Schedule It</h4>
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
