<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ isDark: localStorage.getItem('theme') === 'dark' }" 
      x-init="$watch('isDark', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
      :class="{ 'dark': isDark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Node Center') }}</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg: #FFF8F0;
            --lavender: #E8DAFB;
            --mint: #C1F0DB;
            --peach: #FFD6CC;
            --butter: #FFF3B0;
            --sky: #BDE0FE;
            --pink: #FFB5C2;
            --border: #2D2D2D;
            --text: #1A1A2E;
            --card-bg: #FFFFFF;
            --card-alt: #fafafa;
            --shadow: 4px 4px 0 var(--border);
            --shadow-sm: 3px 3px 0 var(--border);
        }
        
        html.dark {
            --bg: #11111b;
            --lavender: #89b4fa;
            --mint: #a6e3a1;
            --peach: #fab387;
            --butter: #f9e2af;
            --sky: #89dceb;
            --pink: #f38ba8;
            --border: #313244;
            --text: #cdd6f4;
            --card-bg: #1e1e2e;
            --card-alt: #181825;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Space Grotesk', sans-serif; background: var(--bg); color: var(--text); }

        /* Sidebar */
        .neo-sidebar {
            width: 240px; position: fixed; top: 0; left: 0; bottom: 0;
            background: var(--card-bg); border-right: 3px solid var(--border);
            display: flex; flex-direction: column; z-index: 40;
            transition: transform 0.3s ease;
        }
        .neo-sidebar-brand {
            padding: 1.25rem; border-bottom: 3px solid var(--border);
            background: var(--butter);
        }
        .neo-sidebar-brand h1 { font-size: 1.15rem; font-weight: 700; }
        .neo-sidebar-brand p { font-size: 0.65rem; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 0.15rem; }
        .neo-sidebar-nav { padding: 0.75rem; flex: 1; overflow-y: auto; }
        .neo-link {
            display: flex; align-items: center; gap: 0.65rem; padding: 0.65rem 0.85rem;
            border-radius: 0.65rem; color: #444; font-size: 0.85rem; font-weight: 600;
            text-decoration: none; transition: all 0.15s; margin-bottom: 0.25rem;
            border: 2px solid transparent;
        }
        .neo-link:hover { background: var(--card-alt); color: var(--text); }
        .neo-link.active {
            background: var(--lavender); color: var(--text);
            border: 2px solid var(--border); box-shadow: var(--shadow-sm);
        }
        .neo-link svg { width: 18px; height: 18px; flex-shrink: 0; }
        .neo-sidebar-footer {
            padding: 0.75rem; border-top: 3px solid var(--border);
            background: var(--card-alt);
        }
        .neo-user-card {
            display: flex; align-items: center; gap: 0.65rem; padding: 0.5rem;
        }
        .neo-avatar {
            width: 36px; height: 36px; border-radius: 0.5rem;
            background: var(--peach); border: 2px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.8rem; box-shadow: 2px 2px 0 var(--border);
        }

        /* Main */
        .neo-main { margin-left: 240px; min-height: 100vh; }
        .neo-topbar {
            border-bottom: 3px solid var(--border); padding: 1rem 1.75rem;
            background: var(--card-bg); display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 30;
        }
        .neo-topbar h2 { font-size: 1.2rem; font-weight: 700; }
        .neo-page { padding: 1.75rem; }

        /* Cards */
        .neo-card {
            background: var(--card-bg); border: 3px solid var(--border);
            border-radius: 1rem; padding: 1.25rem;
            box-shadow: var(--shadow); transition: all 0.15s;
        }
        .neo-card:hover { transform: translate(-1px, -1px); box-shadow: 5px 5px 0 var(--border); }

        /* Buttons */
        .neo-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.6rem 1.25rem; border: 3px solid var(--border);
            border-radius: 0.65rem; font-size: 0.85rem; font-weight: 700;
            font-family: inherit; cursor: pointer; box-shadow: var(--shadow-sm);
            transition: all 0.15s; text-decoration: none; color: var(--text);
        }
        .neo-btn:active, .neo-btn:hover { transform: translate(2px, 2px); box-shadow: 1px 1px 0 var(--border); }
        .neo-btn-primary { background: var(--lavender); }
        .neo-btn-success { background: var(--mint); }
        .neo-btn-danger { background: var(--pink); }
        .neo-btn-warning { background: var(--butter); }
        .neo-btn-sky { background: var(--sky); }
        .neo-btn-sm {
            padding: 0.4rem 0.85rem; font-size: 0.75rem;
            border-width: 2px; box-shadow: 2px 2px 0 var(--border);
        }
        .neo-btn-sm:active, .neo-btn-sm:hover { transform: translate(1px, 1px); box-shadow: 1px 1px 0 var(--border); }

        /* Stats */
        .neo-stat {
            background: var(--card-bg); border: 3px solid var(--border);
            border-radius: 1rem; padding: 1.25rem;
            box-shadow: var(--shadow); display: flex; align-items: center; gap: 1rem;
        }
        .neo-stat-icon {
            width: 48px; height: 48px; border-radius: 0.75rem;
            border: 2px solid var(--border); display: flex;
            align-items: center; justify-content: center;
            font-size: 1.5rem; box-shadow: 2px 2px 0 var(--border);
        }
        .neo-stat-val { font-size: 1.75rem; font-weight: 700; line-height: 1; }
        .neo-stat-label { font-size: 0.7rem; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.15rem; }

        /* Metric Rows */
        .neo-metric {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.6rem 0.85rem; border: 2px solid var(--border);
            border-radius: 0.5rem; margin-bottom: 0.4rem;
            font-size: 0.8rem; font-weight: 600; background: var(--card-alt);
        }
        .neo-metric-label { color: #555; }
        .neo-metric-value { font-weight: 700; }

        /* Badge */
        .neo-badge {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.2rem 0.6rem; border-radius: 999px;
            font-size: 0.65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            border: 2px solid var(--border);
        }
        .neo-badge-online { background: var(--mint); }
        .neo-badge-offline { background: var(--pink); }

        /* Status Dot */
        .neo-dot { width: 10px; height: 10px; border-radius: 50%; border: 2px solid var(--border); }
        .neo-dot-green { background: #22c55e; }
        .neo-dot-red { background: #ef4444; }

        /* Alerts */
        .neo-alert {
            border: 3px solid var(--border); border-radius: 0.75rem;
            padding: 1rem 1.25rem; box-shadow: var(--shadow-sm);
            margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 600;
        }
        .neo-alert-success { background: var(--mint); }
        .neo-alert-info { background: var(--sky); }
        .neo-alert-token { background: var(--lavender); }

        /* Code Block */
        .neo-code {
            background: #1A1A2E; color: #e2e8f0; border: 3px solid var(--border);
            border-radius: 0.75rem; padding: 1.25rem; font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem; line-height: 1.7; overflow-x: auto;
            box-shadow: var(--shadow);
        }
        .neo-code .kw { color: #c084fc; }
        .neo-code .str { color: #86efac; }
        .neo-code .cmt { color: #64748b; }
        .neo-code .fn { color: #93c5fd; }
        .neo-code .var { color: #fbbf24; }

        /* Inputs */
        .neo-input {
            width: 100%; padding: 0.75rem 1rem; border: 3px solid var(--border);
            border-radius: 0.75rem; font-size: 0.9rem; font-family: inherit;
            background: var(--bg); box-shadow: var(--shadow-sm);
            outline: none; transition: all 0.2s;
        }
        .neo-input:focus { box-shadow: 3px 3px 0 #6C63FF; border-color: #6C63FF; }
        .neo-input::placeholder { color: #aaa; }
        .neo-label { display: block; font-weight: 700; font-size: 0.8rem; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.04em; }

        /* Modal */
        .neo-modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.4);
            display: flex; align-items: center; justify-content: center;
            z-index: 100; padding: 1rem;
        }
        .neo-modal {
            background: var(--card-bg); border: 3px solid var(--border);
            border-radius: 1rem; padding: 1.75rem; max-width: 500px;
            width: 100%; box-shadow: 8px 8px 0 var(--border);
        }
        .neo-token-box {
            background: var(--bg); border: 3px solid var(--border);
            border-radius: 0.75rem; padding: 0.85rem 1rem;
            font-family: 'JetBrains Mono', monospace; font-size: 0.75rem;
            word-break: break-all; box-shadow: inset 2px 2px 0 rgba(0,0,0,0.05);
        }

        /* Mobile */
        .neo-mobile-toggle { display: none; }
        .neo-overlay { display: none; }
        @media (max-width: 768px) {
            .neo-sidebar { transform: translateX(-100%); }
            .neo-sidebar.open { transform: translateX(0); }
            .neo-main { margin-left: 0; }
            .neo-mobile-toggle { display: block; position: fixed; top: 0.75rem; left: 0.75rem; z-index: 50; background: var(--card-bg); border: 2px solid var(--border); border-radius: 0.5rem; padding: 0.4rem; cursor: pointer; box-shadow: 2px 2px 0 var(--border); }
            .neo-overlay.open { display: block; position: fixed; inset: 0; background: rgba(0,0,0,0.3); z-index: 35; }
            .neo-page { padding: 1rem; }
            .neo-topbar { padding: 1rem; padding-left: 3rem; }
        }

        /* Doc Steps */
        .neo-step { display: flex; gap: 0.85rem; margin-bottom: 1.25rem; }
        .neo-step-num {
            width: 32px; height: 32px; min-width: 32px; border-radius: 50%;
            background: var(--lavender); border: 2px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.8rem; box-shadow: 2px 2px 0 var(--border);
        }
        .neo-step h4 { font-size: 0.9rem; font-weight: 700; margin-bottom: 0.25rem; }
        .neo-step p { font-size: 0.8rem; color: #555; line-height: 1.6; }
    </style>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body x-data="{ sidebarOpen: false }">
    <div class="neo-overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>
    <button class="neo-mobile-toggle" @click="sidebarOpen = !sidebarOpen">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <aside class="neo-sidebar" :class="{ 'open': sidebarOpen }">
        <div class="neo-sidebar-brand" style="display: flex; flex-direction: column; align-items: flex-start; gap: 0.25rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <img src="/logo.png" alt="Logo" style="width: 28px; height: 28px; object-fit: contain;">
                <h1 style="margin: 0;">Node Center</h1>
            </div>
            <p style="margin: 0;">App Monitor</p>
        </div>
        <nav class="neo-sidebar-nav">
            <a href="{{ route('dashboard') }}" class="neo-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('apps.index') }}" class="neo-link {{ request()->routeIs('apps.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/></svg>
                My Apps
            </a>
            <a href="{{ route('documentation') }}" class="neo-link {{ request()->routeIs('documentation') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Docs
            </a>
            <a href="{{ route('settings.index') }}" class="neo-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
            
            <div style="margin-top: 2rem;">
                <button @click="isDark = !isDark" class="neo-link" style="width: 100%; text-align: left; background: none; cursor: pointer;">
                    <template x-if="!isDark">
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            Dark Mode
                        </div>
                    </template>
                    <template x-if="isDark">
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Light Mode
                        </div>
                    </template>
                </button>
            </div>
        </nav>
        <div class="neo-sidebar-footer">
            <div class="neo-user-card">
                <div class="neo-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 700; font-size: 0.8rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.65rem; color: #888;">Admin</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: none; border: none; cursor: pointer; color: #999; padding: 0.25rem;" title="Log out">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="neo-main">
        <div class="neo-topbar">
            @isset($header) {{ $header }} @endisset
            <div style="font-size: 0.75rem; color: #999;">{{ now()->format('D, d M Y') }}</div>
        </div>
        <div class="neo-page">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
