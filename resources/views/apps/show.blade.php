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
            <p style="color: #666; font-size: 0.85rem;">This application hasn't sent any metrics in the last 24 hours.</p>
        </div>
    @else
        <!-- Stats Summary -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
            <div class="neo-stat">
                <div class="neo-stat-icon" style="background: var(--lavender);">💻</div>
                <div>
                    <div class="neo-stat-val">{{ number_format($metrics->last()->cpu_usage) }}%</div>
                    <div class="neo-stat-label">Current CPU</div>
                </div>
            </div>
            <div class="neo-stat">
                <div class="neo-stat-icon" style="background: var(--mint);">🧠</div>
                <div>
                    <div class="neo-stat-val">{{ number_format($metrics->last()->memory_usage) }}MB</div>
                    <div class="neo-stat-label">Current Memory</div>
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

        <!-- Charts Grid -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;">
            
            <div class="neo-card">
                <h3 style="font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--border); padding-bottom: 0.5rem;">💻 CPU & Memory Usage (24h)</h3>
                <div style="height: 300px; position: relative;">
                    <canvas id="resourceChart"></canvas>
                </div>
            </div>

            <div class="neo-card">
                <h3 style="font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--border); padding-bottom: 0.5rem;">🗄️ Database & Cache Latency (24h)</h3>
                <div style="height: 300px; position: relative;">
                    <canvas id="latencyChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Chart.js Setup -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Prepare Data
                const rawMetrics = {!! json_encode($metrics->map(function($m) {
                    return [
                        'time' => $m->created_at->format('H:i'),
                        'cpu' => $m->cpu_usage,
                        'mem' => $m->memory_usage,
                        'db' => $m->db_latency,
                        'cache' => $m->cache_latency,
                        'users' => $m->active_users
                    ];
                })) !!};

                const labels = rawMetrics.map(m => m.time);
                
                // Chart Defaults for Neo-Brutalist look
                Chart.defaults.font.family = "'Space Grotesk', sans-serif";
                Chart.defaults.color = "#555";
                Chart.defaults.scale.grid.color = "rgba(0,0,0,0.05)";
                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        line: { borderWidth: 4, tension: 0.3 },
                        point: { radius: 3, hitRadius: 10, hoverRadius: 6 }
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
                                backgroundColor: '#FFB5C2',
                                yAxisID: 'y'
                            },
                            {
                                label: 'Memory (MB)',
                                data: rawMetrics.map(m => m.mem),
                                borderColor: '#89b4fa', // var(--lavender equivalent)
                                backgroundColor: '#89b4fa',
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
                                backgroundColor: '#f59e0b',
                            },
                            {
                                label: 'Cache Latency (ms)',
                                data: rawMetrics.map(m => m.cache),
                                borderColor: '#10b981', // emerald
                                backgroundColor: '#10b981',
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
