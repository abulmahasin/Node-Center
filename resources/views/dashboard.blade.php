<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.2rem; font-weight: 700;">📊 Dashboard</h2>
    </x-slot>

    <div x-data="{ 
        modalOpen: false, 
        modalTitle: '', 
        modalType: '', 
        modalData: null,
        openModal(title, type, data) {
            this.modalTitle = title;
            this.modalType = type;
            this.modalData = data || [];
            this.modalOpen = true;
        }
    }">
        <!-- Stats -->
        @php
            $onlineCount = $apps->filter(function($a) { $m = $a->metrics->first(); return $m && $m->created_at->diffInMinutes(now()) <= 5; })->count();
            $offlineCount = $apps->count() - $onlineCount;
        @endphp
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.75rem;">
            <div class="neo-stat">
                <div class="neo-stat-icon" style="background: var(--sky);">📦</div>
                <div><div class="neo-stat-val">{{ $apps->count() }}</div><div class="neo-stat-label">Total Apps</div></div>
            </div>
            <div class="neo-stat">
                <div class="neo-stat-icon" style="background: var(--mint);">✅</div>
                <div><div class="neo-stat-val" style="color: #16a34a;">{{ $onlineCount }}</div><div class="neo-stat-label">Online</div></div>
            </div>
            <div class="neo-stat">
                <div class="neo-stat-icon" style="background: var(--pink);">⚠️</div>
                <div><div class="neo-stat-val" style="color: #dc2626;">{{ $offlineCount }}</div><div class="neo-stat-label">Offline</div></div>
            </div>
        </div>

        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 1rem; font-weight: 700;">Monitored Applications</h3>
            <div style="display: flex; align-items: center; gap: 0.4rem;">
                <span class="neo-dot neo-dot-green"></span>
                <span style="font-size: 0.7rem; color: #888; font-weight: 600;">Live · 15s refresh</span>
            </div>
        </div>

        <div id="metrics-container">
            @if($apps->isEmpty())
                <div class="neo-card" style="text-align: center; padding: 3rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">📭</div>
                    <h3 style="font-weight: 700; margin-bottom: 0.35rem;">No apps yet</h3>
                    <p style="color: #666; font-size: 0.85rem; margin-bottom: 1.25rem;">Register your first app to start monitoring</p>
                    <a href="{{ route('apps.create') }}" class="neo-btn neo-btn-primary">+ New Application</a>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 1.25rem;">
                    @foreach($apps as $app)
                        @php
                            $m = $app->metrics->first();
                            $isOnline = $app->status === 'online';
                            $isAgentStale = $m ? $m->created_at->diffInMinutes(now()) > 5 : true;
                        @endphp
                        <div class="neo-card" style="border-left: 6px solid {{ $isOnline ? '#22c55e' : '#ef4444' }}; padding: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                                <div>
                                    <h4 style="font-weight: 800; font-size: 1.05rem;">
                                        <a href="{{ route('apps.show', $app->id) }}" style="text-decoration: none; color: inherit;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">
                                            {{ $app->name }}
                                        </a>
                                        @if($m && isset($m->app_env))
                                            <span style="font-size: 0.65rem; background: {{ $m->app_env === 'production' ? '#16a34a' : '#d97706' }}; color: white; padding: 2px 6px; border-radius: 4px; margin-left: 6px; vertical-align: middle;">{{ strtoupper($m->app_env) }}</span>
                                        @endif
                                    </h4>
                                    <a href="{{ $app->url }}" target="_blank" style="font-size: 0.75rem; color: #888; text-decoration: none;">{{ $app->url }}</a>
                                    <p style="font-size: 0.8rem; color: #666; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                                        {{ $app->type ?? 'General App' }}
                                        
                                        @if(!$isOnline)
                                            <span class="neo-badge neo-badge-offline">AGENT OFFLINE</span>
                                        @elseif($isAgentStale)
                                            <span class="neo-badge" style="background: var(--butter); color: #854d0e;">AGENT STALE</span>
                                        @else
                                            <span class="neo-badge neo-badge-online">AGENT ONLINE</span>
                                        @endif
                                        
                                        <!-- Active Ping Status -->
                                        @if($app->ping_status === 'up')
                                            <span class="neo-badge" style="background: var(--mint); color: #166534; border-color: #16a34a;" title="Last checked: {{ $app->last_active_ping_at?->diffForHumans() }}">
                                                <span class="neo-dot neo-dot-green" style="margin-right: 4px;"></span> UP ({{ $app->ping_response_time }}ms)
                                            </span>
                                        @elseif($app->ping_status === 'down')
                                            <span class="neo-badge" style="background: var(--pink); color: #9f1239; border-color: #be123c;" title="Error: {{ $app->ping_error }}">
                                                <span class="neo-dot neo-dot-red" style="margin-right: 4px;"></span> DOWN
                                            </span>
                                        @else
                                            <span class="neo-badge" style="background: #e5e7eb; color: #4b5563;">PING: UNKNOWN</span>
                                        @endif
                                        
                                        <!-- Manual Ping Button -->
                                        <form method="POST" action="{{ route('apps.ping', $app->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="neo-btn neo-btn-sm" style="background: var(--card-alt); padding: 0.15rem 0.5rem; font-size: 0.65rem;" title="Test Ping Now">
                                                🔄 Ping
                                            </button>
                                        </form>

                                        <!-- SSL Status -->
                                        @if($app->ssl_expires_at)
                                            @php
                                                $sslExpiry = \Carbon\Carbon::parse($app->ssl_expires_at);
                                                $daysLeft = (int) now()->diffInDays($sslExpiry, false);
                                            @endphp
                                            @if($daysLeft < 0)
                                                <span class="neo-badge" style="background: var(--pink); color: #9f1239; border-color: #be123c;" title="SSL Expired: {{ $sslExpiry->format('d M Y') }}">🔒 EXPIRED</span>
                                            @elseif($daysLeft <= 7)
                                                <span class="neo-badge" style="background: var(--butter); color: #854d0e; border-color: #ca8a04;" title="SSL Expiring: {{ $sslExpiry->format('d M Y') }}">🔒 {{ $daysLeft }} hari lagi!</span>
                                            @elseif($daysLeft <= 30)
                                                <span class="neo-badge" style="background: var(--butter); color: #854d0e; border-color: #ca8a04;" title="SSL Valid: {{ $sslExpiry->format('d M Y') }} ({{ $app->ssl_issuer }})">🔒 {{ $daysLeft }} hari</span>
                                            @else
                                                <span class="neo-badge" style="background: var(--mint); color: #166534; border-color: #16a34a;" title="SSL Valid: {{ $sslExpiry->format('d M Y') }} ({{ $app->ssl_issuer }})">🔒 {{ $daysLeft }} hari</span>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    @if($m && isset($m->security_warnings) && count($m->security_warnings) > 0)
                                        <button @click="openModal('Security Check', 'security', {{ json_encode($m->security_warnings) }})" 
                                                class="neo-btn" style="background: var(--pink); border-color: #be123c; padding: 0.4rem 0.6rem; color: #9f1239; box-shadow: 2px 2px 0 #be123c;" 
                                                title="Security Issues Detected">
                                            🛡️ {{ count($m->security_warnings) }} Warnings
                                        </button>
                                    @elseif($m)
                                        <div class="neo-badge" style="background: var(--mint); color: #166534; padding: 0.5rem 0.75rem;">🛡️ Secured</div>
                                    @endif
                                </div>
                            </div>

                            @if($m)
                                @if(isset($m->php_version) && isset($m->laravel_version))
                                <!-- Framework Info -->
                                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                                    <span style="background: var(--sky); font-size: 0.65rem; font-weight: 700; padding: 4px 8px; border-radius: 4px; border: 1px solid var(--border);">🐘 PHP {{ $m->php_version }}</span>
                                    <span style="background: var(--pink); font-size: 0.65rem; font-weight: 700; padding: 4px 8px; border-radius: 4px; border: 1px solid var(--border);">🔥 Laravel {{ $m->laravel_version }}</span>
                                </div>
                                @endif

                                <!-- Server Resources -->
                                <div style="font-size: 0.7rem; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Server Resources</div>
                                <div class="neo-metric"><span class="neo-metric-label">🔥 CPU Usage</span><span class="neo-metric-value" style="color: {{ $m->cpu_usage > 80 ? '#dc2626' : ($m->cpu_usage > 60 ? '#d97706' : '#16a34a') }}">{{ number_format($m->cpu_usage, 1) }}%</span></div>
                                <div class="neo-metric"><span class="neo-metric-label">💾 Memory Usage</span><span class="neo-metric-value" style="color: {{ $m->memory_usage > 80 ? '#dc2626' : ($m->memory_usage > 60 ? '#d97706' : '#16a34a') }}">{{ number_format($m->memory_usage, 1) }} MB</span></div>
                                <div class="neo-metric"><span class="neo-metric-label">💽 Disk Space Used</span><span class="neo-metric-value" style="color: {{ $m->disk_usage > 90 ? '#dc2626' : ($m->disk_usage > 75 ? '#d97706' : '#16a34a') }}">{{ number_format($m->disk_usage, 1) }}%</span></div>

                                <!-- Application Health -->
                                <div style="font-size: 0.7rem; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 0.05em; margin: 1rem 0 0.5rem 0;">Application Health</div>
                                <div class="neo-metric"><span class="neo-metric-label">👥 Active Users</span><span class="neo-metric-value" style="color: #6C63FF;">{{ number_format($m->active_users) }}</span></div>
                                
                                @if(isset($m->db_latency))
                                <div class="neo-metric" style="cursor: pointer; transition: all 0.1s;" 
                                     @click="openModal('Slow Queries Log', 'slow_query', {{ json_encode($m->slow_queries ?? []) }})"
                                     onmouseover="this.style.background='var(--butter)'; this.style.borderColor='var(--border)';" 
                                     onmouseout="this.style.background='#fafafa';">
                                    <span class="neo-metric-label">🗄️ DB Latency (Slow Queries 🔍)</span>
                                    <span class="neo-metric-value" style="color: {{ $m->db_latency > 500 ? '#dc2626' : ($m->db_latency > 200 ? '#d97706' : '#16a34a') }}">{{ number_format($m->db_latency) }} ms</span>
                                </div>
                                @endif

                                @if(isset($m->cache_latency))
                                <div class="neo-metric"><span class="neo-metric-label">⚡ Cache Latency</span><span class="neo-metric-value" style="color: {{ $m->cache_latency > 100 ? '#dc2626' : ($m->cache_latency > 50 ? '#d97706' : '#16a34a') }}">{{ number_format($m->cache_latency) }} ms</span></div>
                                @endif
                                
                                <div class="neo-metric"><span class="neo-metric-label">🌐 API Response</span><span class="neo-metric-value">{{ number_format($m->response_time, 1) }} ms</span></div>

                                <!-- Background Jobs & Errors (Clickable) -->
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 1rem;">
                                    @if(isset($m->pending_jobs))
                                    <div @click="openModal('Queue Jobs Details', 'queue', {{ json_encode($m->queue_details ?? []) }})" 
                                         style="background: var(--bg); border: 2px solid var(--border); border-radius: 0.5rem; padding: 0.75rem; text-align: center; cursor: pointer; transition: all 0.15s;" 
                                         onmouseover="this.style.transform='translate(-2px, -2px)'; this.style.boxShadow='3px 3px 0 var(--border)';" 
                                         onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                                        <div style="font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Queue Jobs 🔍</div>
                                        <div style="font-size: 1.1rem; font-weight: 800; margin-top: 0.25rem;">
                                            <span style="color: #6C63FF;" title="Pending Jobs">{{ number_format($m->pending_jobs) }}</span>
                                            <span style="color: #ccc; margin: 0 0.25rem;">/</span>
                                            <span style="color: {{ $m->failed_jobs > 0 ? '#dc2626' : '#16a34a' }};" title="Failed Jobs">{{ number_format($m->failed_jobs) }}</span>
                                        </div>
                                    </div>
                                    @endif

                                    @if(isset($m->error_count))
                                    <div @click="openModal('Recent Error Logs', 'error', {{ json_encode($m->error_details ?? []) }})" 
                                         style="background: {{ $m->error_count > 0 ? 'var(--pink)' : 'var(--mint)' }}; border: 2px solid var(--border); border-radius: 0.5rem; padding: 0.75rem; text-align: center; cursor: pointer; transition: all 0.15s;" 
                                         onmouseover="this.style.transform='translate(-2px, -2px)'; this.style.boxShadow='3px 3px 0 var(--border)';" 
                                         onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                                        <div style="font-size: 0.65rem; font-weight: 700; color: {{ $m->error_count > 0 ? '#9f1239' : '#166534' }}; text-transform: uppercase;">Recent Errors 🔍</div>
                                        <div style="font-size: 1.1rem; font-weight: 800; margin-top: 0.25rem; color: {{ $m->error_count > 0 ? '#be123c' : '#15803d' }};">
                                            {{ number_format($m->error_count) }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                @if(isset($m->schedule_details) && is_array($m->schedule_details) && count($m->schedule_details) > 0)
                                <button @click="openModal('Scheduled Tasks', 'schedule', {{ json_encode($m->schedule_details) }})" class="neo-btn" style="width: 100%; justify-content: center; margin-top: 0.75rem; background: var(--butter); padding: 0.5rem; font-size: 0.75rem;">
                                   🕒 View {{ count($m->schedule_details) }} Scheduled Tasks
                                </button>
                                @endif

                                <p style="font-size: 0.65rem; color: #aaa; margin-top: 1rem; text-align: right;">Updated {{ $m->created_at->diffForHumans() }}</p>
                            @else
                                <div style="text-align: center; padding: 2rem 0;">
                                    <div style="font-size: 2rem; margin-bottom: 0.75rem;">⏳</div>
                                    <h4 style="font-weight: 700; margin-bottom: 0.25rem;">Waiting for metrics</h4>
                                    <p style="color: #888; font-size: 0.8rem;">Ensure your app's scheduler is running.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- The Modal -->
        <div x-show="modalOpen" class="neo-modal-overlay" style="display: none;" x-transition>
            <div class="neo-modal" @click.stop style="max-width: 600px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 3px solid var(--border); padding-bottom: 1rem;">
                    <h3 style="font-weight: 800; font-size: 1.2rem;" x-text="modalTitle"></h3>
                    <button @click="modalOpen = false" class="neo-btn neo-btn-sm" style="background: var(--pink);">✖ Close</button>
                </div>
                
                <div style="max-height: 450px; overflow-y: auto;">
                    
                    <!-- Slow Queries Modal -->
                    <template x-if="modalType === 'slow_query'">
                        <div>
                            <template x-if="modalData && modalData.length > 0">
                                <div class="neo-code" style="background: #1e1e2e; color: #fbbf24; padding: 1rem; text-align: left;">
                                    <template x-for="query in modalData">
                                        <div style="margin-bottom: 0.8rem; border-bottom: 1px solid #333; padding-bottom: 0.8rem; font-size: 0.7rem; white-space: pre-wrap;" x-text="query"></div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!modalData || modalData.length === 0">
                                <div style="text-align: center; padding: 2rem; font-weight: 700; color: #16a34a;">⚡ Database is blazing fast! No slow queries detected.</div>
                            </template>
                        </div>
                    </template>

                    <!-- Error Modal -->
                    <template x-if="modalType === 'error'">
                        <div>
                            <template x-if="modalData && modalData.length > 0">
                                <div class="neo-code" style="background: #1e1e2e; color: #f87171; padding: 1rem; text-align: left;">
                                    <template x-for="err in modalData">
                                        <div style="margin-bottom: 0.8rem; border-bottom: 1px solid #333; padding-bottom: 0.8rem; font-size: 0.7rem; white-space: pre-wrap;" x-text="err"></div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!modalData || modalData.length === 0">
                                <div style="text-align: center; padding: 2rem; font-weight: 700; color: #16a34a;">🎉 No recent errors!</div>
                            </template>
                        </div>
                    </template>

                    <!-- Queue Modal -->
                    <template x-if="modalType === 'queue'">
                        <div>
                            <h4 style="font-weight: 700; margin-bottom: 0.5rem;">❌ Failed Jobs</h4>
                            <template x-if="modalData && modalData.failed && modalData.failed.length > 0">
                                <div style="margin-bottom: 1rem; border: 2px solid var(--border); border-radius: 0.5rem; overflow: hidden;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem;">
                                        <thead style="background: var(--pink); border-bottom: 2px solid var(--border);">
                                            <tr><th style="padding: 0.5rem; text-align: left;">Job Name</th><th style="padding: 0.5rem; text-align: left;">Failed At</th></tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="job in modalData.failed">
                                                <tr style="border-bottom: 1px solid var(--border);">
                                                    <td style="padding: 0.5rem; font-weight: 700;" x-text="job.name"></td>
                                                    <td style="padding: 0.5rem; color: #dc2626;" x-text="job.failed_at"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                            <template x-if="!modalData || !modalData.failed || modalData.failed.length === 0">
                                <p style="font-size: 0.75rem; color: #666; margin-bottom: 1rem;">No failed jobs.</p>
                            </template>

                            <h4 style="font-weight: 700; margin-bottom: 0.5rem;">⏳ Pending Jobs (Oldest 5)</h4>
                            <template x-if="modalData && modalData.pending && modalData.pending.length > 0">
                                <div style="border: 2px solid var(--border); border-radius: 0.5rem; overflow: hidden;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem;">
                                        <thead style="background: var(--butter); border-bottom: 2px solid var(--border);">
                                            <tr><th style="padding: 0.5rem; text-align: left;">Job Name</th><th style="padding: 0.5rem; text-align: left;">Queued At</th></tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="job in modalData.pending">
                                                <tr style="border-bottom: 1px solid var(--border);">
                                                    <td style="padding: 0.5rem; font-weight: 700;" x-text="job.name"></td>
                                                    <td style="padding: 0.5rem; color: #d97706;" x-text="job.created_at"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                            <template x-if="!modalData || !modalData.pending || modalData.pending.length === 0">
                                <p style="font-size: 0.75rem; color: #666;">No pending jobs.</p>
                            </template>
                        </div>
                    </template>

                    <!-- Schedule Modal -->
                    <template x-if="modalType === 'schedule'">
                        <div>
                            <template x-if="modalData && modalData.length > 0">
                                <div style="border: 2px solid var(--border); border-radius: 0.5rem; overflow: hidden;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem;">
                                        <thead style="background: var(--sky); border-bottom: 2px solid var(--border);">
                                            <tr>
                                                <th style="padding: 0.5rem; text-align: left;">Command/Description</th>
                                                <th style="padding: 0.5rem; text-align: left;">Cron</th>
                                                <th style="padding: 0.5rem; text-align: left;">Next Run</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="sch in modalData">
                                                <tr style="border-bottom: 1px solid var(--border);">
                                                    <td style="padding: 0.5rem;">
                                                        <div style="font-weight: 700;" x-text="sch.command || 'Closure'"></div>
                                                        <div style="font-size: 0.65rem; color: #666;" x-text="sch.description || ''"></div>
                                                    </td>
                                                    <td style="padding: 0.5rem; font-family: monospace; font-weight: 800; color: #d97706;" x-text="sch.expression"></td>
                                                    <td style="padding: 0.5rem; color: #16a34a; font-weight: 600;" x-text="sch.next_run"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                            <template x-if="!modalData || modalData.length === 0">
                                <p style="text-align: center; padding: 2rem;">No scheduled tasks found.</p>
                            </template>
                        </div>
                    </template>
                    <!-- Security Modal -->
                    <template x-if="modalType === 'security'">
                        <div>
                            <div style="background: #fef2f2; border: 2px solid #f87171; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                                <h4 style="color: #b91c1c; font-weight: 800; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                    ⚠️ Critical Vulnerabilities Found
                                </h4>
                                <p style="font-size: 0.75rem; color: #991b1b;">
                                    Please fix the following issues in the target application's <code>.env</code> file immediately to secure your server.
                                </p>
                            </div>
                            
                            <ul style="list-style: none; padding: 0;">
                                <template x-for="warning in modalData">
                                    <li style="margin-bottom: 0.75rem; padding: 0.75rem; background: var(--bg); border-left: 4px solid #ef4444; border-radius: 0.25rem; font-size: 0.8rem; font-weight: 600; color: #555;">
                                        <span x-text="warning"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>

    <script>
        setInterval(() => {
            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const el = doc.getElementById('metrics-container');
                if (el) {
                    document.getElementById('metrics-container').innerHTML = el.innerHTML;
                    // Note: Alpine v3 MutationObserver will automatically re-parse the new HTML!
                }
            })
            .catch(() => {});
        }, 15000);
    </script>
</x-app-layout>
