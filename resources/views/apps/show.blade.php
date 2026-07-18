<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <a href="{{ route('dashboard') }}" class="neo-btn neo-btn-sm" style="background: var(--card-alt);">⬅ Back</a>
                <h2 style="font-size: 1.2rem; font-weight: 700;">📈 {{ $app->name }} Analytics</h2>
            </div>
            <a href="{{ $app->url }}" target="_blank" class="neo-btn neo-btn-sky neo-btn-sm" style="text-decoration: none;">Visit App ↗</a>
        </div>
    </x-slot>

    @if($metrics->isEmpty())
        <div class="neo-card" style="text-align: center; padding: 3rem;">
            <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">📭</div>
            <h3 style="font-weight: 700; margin-bottom: 0.35rem;">No Data Available</h3>
            <p style="color: #666; font-size: 0.85rem;">This application hasn't sent any metrics for the selected time range.</p>
            
            <div style="margin-top: 2rem;">
                <a href="{{ route('apps.show', ['app' => $app->id, 'range' => '24h']) }}" class="neo-btn neo-btn-sm {{ $range === '24h' ? 'neo-btn-primary' : '' }}">24 Hours</a>
                <a href="{{ route('apps.show', ['app' => $app->id, 'range' => '7d']) }}" class="neo-btn neo-btn-sm {{ $range === '7d' ? 'neo-btn-primary' : '' }}">7 Days</a>
                <a href="{{ route('apps.show', ['app' => $app->id, 'range' => '30d']) }}" class="neo-btn neo-btn-sm {{ $range === '30d' ? 'neo-btn-primary' : '' }}">30 Days</a>
            </div>
        </div>
    @else
        <!-- Time Range Selector -->
        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-bottom: 1.5rem;">
            <a href="{{ route('apps.show', ['app' => $app->id, 'range' => '24h']) }}" class="neo-btn neo-btn-sm" style="{{ $range === '24h' ? 'background: var(--primary);' : 'background: white;' }}">24 Hours</a>
            <a href="{{ route('apps.show', ['app' => $app->id, 'range' => '7d']) }}" class="neo-btn neo-btn-sm" style="{{ $range === '7d' ? 'background: var(--primary);' : 'background: white;' }}">7 Days</a>
            <a href="{{ route('apps.show', ['app' => $app->id, 'range' => '30d']) }}" class="neo-btn neo-btn-sm" style="{{ $range === '30d' ? 'background: var(--primary);' : 'background: white;' }}">30 Days</a>
        </div>

        <!-- Stats Summary -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
            <div class="neo-stat">
                <div class="neo-stat-icon" style="background: var(--lavender);">💻</div>
                <div>
                    <div class="neo-stat-val">{{ number_format($metrics->last()->cpu_usage) }}%</div>
                    <div class="neo-stat-label">Latest CPU</div>
                </div>
            </div>
            <div class="neo-stat">
                <div class="neo-stat-icon" style="background: var(--mint);">🧠</div>
                <div>
                    <div class="neo-stat-val">{{ number_format($metrics->last()->memory_usage) }}MB</div>
                    <div class="neo-stat-label">Latest Memory</div>
                </div>
            </div>
            <div class="neo-stat">
                <div class="neo-stat-icon" style="background: var(--sky);">👥</div>
                <div>
                    <div class="neo-stat-val">{{ number_format($metrics->last()->active_users) }}</div>
                    <div class="neo-stat-label">Active Users</div>
                </div>
            </div>
        </div>

        @php
            $rangeLabel = $range === '30d' ? '30 Days' : ($range === '7d' ? '7 Days' : '24 Hours');
        @endphp

        <!-- Charts Grid -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;">
            
            <div class="neo-card">
                <h3 style="font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--border); padding-bottom: 0.5rem;">💻 CPU & Memory Usage ({{ $rangeLabel }})</h3>
                <div style="height: 350px; position: relative;">
                    <canvas id="resourceChart"></canvas>
                </div>
            </div>

            <div class="neo-card">
                <h3 style="font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--border); padding-bottom: 0.5rem;">🗄️ Database & Cache Latency ({{ $rangeLabel }})</h3>
                <div style="height: 350px; position: relative;">
                    <canvas id="latencyChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Incident History -->
        <div class="neo-card" style="margin-top: 1.5rem;">
            <h3 style="font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--border); padding-bottom: 0.5rem;">📜 Incident History (Downtime)</h3>
            
            @if($incidents->isEmpty())
                <div style="text-align: center; padding: 2rem; color: #16a34a; font-weight: 700;">
                    🎉 Perfect uptime! No incidents recorded.
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                        <thead>
                            <tr style="background: var(--bg); border-bottom: 2px solid var(--border);">
                                <th style="padding: 0.75rem;">Status</th>
                                <th style="padding: 0.75rem;">Started At</th>
                                <th style="padding: 0.75rem;">Resolved At</th>
                                <th style="padding: 0.75rem;">Duration</th>
                                <th style="padding: 0.75rem;">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incidents as $incident)
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td style="padding: 0.75rem;">
                                        @if(!$incident->resolved_at)
                                            <span class="neo-badge" style="background: var(--pink); color: #9f1239; animation: pulse 2s infinite;">🔴 ONGOING</span>
                                        @else
                                            <span class="neo-badge" style="background: var(--mint); color: #166534;">🟢 RESOLVED</span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem; font-weight: 600;">{{ $incident->started_at->format('M d, Y H:i') }}</td>
                                    <td style="padding: 0.75rem; font-weight: 600;">
                                        {{ $incident->resolved_at ? $incident->resolved_at->format('M d, Y H:i') : '-' }}
                                    </td>
                                    <td style="padding: 0.75rem; font-family: monospace;">
                                        @if($incident->resolved_at)
                                            {{ $incident->started_at->diffForHumans($incident->resolved_at, true) }}
                                        @else
                                            {{ $incident->started_at->diffForHumans(null, true) }}...
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem; color: #dc2626;">{{ $incident->error_message ?? 'Offline' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <style>
                    @keyframes pulse {
                        0% { opacity: 1; }
                        50% { opacity: 0.5; }
                        100% { opacity: 1; }
                    }
                </style>
            @endif
        </div>

        <!-- Chart.js Setup -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Prepare Data
                const range = '{{ $range }}';
                const rawMetrics = {!! json_encode($metrics->map(function($m) {
                    return [
                        'timestamp' => $m->created_at->toIso8601String(),
                        'cpu' => round($m->cpu_usage, 2),
                        'mem' => round($m->memory_usage, 2),
                        'db' => round($m->db_latency, 2),
                        'cache' => round($m->cache_latency, 2),
                        'users' => $m->active_users
                    ];
                })) !!};

                const formatTimestamp = (timestamp) => {
                    const dt = new Date(timestamp);
                    if (range === '7d') {
                        return new Intl.DateTimeFormat('en', {
                            weekday: 'short',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false,
                        }).format(dt);
                    }
                    if (range === '30d') {
                        return new Intl.DateTimeFormat('en', {
                            month: 'short',
                            day: '2-digit',
                        }).format(dt);
                    }
                    return new Intl.DateTimeFormat('en', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false,
                    }).format(dt);
                };

                const labels = rawMetrics.map(m => formatTimestamp(m.timestamp));
                
                // Chart Defaults for Neo-Brutalist look
                Chart.defaults.font.family = "'Space Grotesk', sans-serif";
                Chart.defaults.color = "#555";
                Chart.defaults.scale.grid.color = "rgba(0,0,0,0.05)";
                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        line: { borderWidth: 3, tension: 0.3 },
                        point: { radius: {{ $range === '24h' ? '3' : '1' }}, hitRadius: 10, hoverRadius: 6 }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { font: { weight: 'bold' } } },
                        tooltip: { backgroundColor: '#1A1A2E', padding: 12, cornerRadius: 8, titleFont: { size: 14 } }
                    }
                };

                // 1. Resource Chart (CPU & Mem)
                new Chart(document.getElementById('resourceChart'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'CPU Usage (%)',
                                data: rawMetrics.map(m => m.cpu),
                                borderColor: '#FFB5C2', // var(--pink)
                                backgroundColor: 'rgba(255, 181, 194, 0.2)',
                                fill: true,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Memory (MB)',
                                data: rawMetrics.map(m => m.mem),
                                borderColor: '#89b4fa', // var(--lavender equivalent)
                                backgroundColor: 'rgba(137, 180, 250, 0.2)',
                                fill: true,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: { type: 'linear', display: true, position: 'left', min: 0, title: { display: true, text: '%' } },
                            y1: { type: 'linear', display: true, position: 'right', min: 0, title: { display: true, text: 'MB' }, grid: { drawOnChartArea: false } }
                        }
                    }
                });

                // 2. Latency Chart (DB & Cache)
                new Chart(document.getElementById('latencyChart'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'DB Latency (ms)',
                                data: rawMetrics.map(m => m.db),
                                borderColor: '#f59e0b', // amber
                                backgroundColor: 'rgba(245, 158, 11, 0.2)',
                                fill: true
                            },
                            {
                                label: 'Cache Latency (ms)',
                                data: rawMetrics.map(m => m.cache),
                                borderColor: '#10b981', // emerald
                                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                                fill: true
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: { min: 0, title: { display: true, text: 'ms' } }
                        }
                    }
                });
            });
        </script>
    @endif
</x-app-layout>
