<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Node Center — Professional App Monitor</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --bg-color: #F8F9FA;
            --text-main: #111111;
            --text-muted: #555555;
            --border-color: #111111;
            --primary: #E2E8F0;
            --accent: #111111;
        }

        body { 
            font-family: 'Space Grotesk', sans-serif; 
            background: var(--bg-color); 
            color: var(--text-main); 
            min-height: 100vh; 
            position: relative; 
            overflow-x: hidden; 
        }

        /* Architectural Grid Background */
        .bg-grid {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, rgba(17, 17, 17, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(17, 17, 17, 0.04) 1px, transparent 1px);
            z-index: -2;
            pointer-events: none;
        }

        /* Atmospheric Logo Glow */
        .bg-logo-blur {
            position: fixed;
            top: 50%; left: 50%;
            width: 800px; height: 800px;
            background-image: url('/logo.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.02;
            filter: blur(15px);
            z-index: -1;
            pointer-events: none;
            animation: atmosphericPulse 15s ease-in-out infinite alternate;
        }

        @keyframes atmosphericPulse {
            0% { transform: translate(-50%, -50%) rotate(-5deg) scale(0.9); opacity: 0.01; }
            100% { transform: translate(-50%, -50%) rotate(5deg) scale(1.1); opacity: 0.03; }
        }

        /* Base Structural Classes */
        .structural-border { 
            border: 2px solid var(--border-color); 
        }
        
        .structural-shadow { 
            box-shadow: 4px 4px 0 var(--border-color); 
            transition: transform 0.2s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.2s cubic-bezier(0.2, 0.8, 0.2, 1); 
        }
        
        .structural-shadow:hover { 
            transform: translate(-2px, -2px); 
            box-shadow: 6px 6px 0 var(--border-color); 
        }
        
        .structural-shadow:active { 
            transform: translate(2px, 2px); 
            box-shadow: 2px 2px 0 var(--border-color); 
        }

        /* Navbar */
        .navbar {
            display: flex; justify-content: space-between; align-items: center; 
            padding: 1.25rem 2.5rem; background: rgba(248, 249, 250, 0.9); 
            backdrop-filter: blur(10px);
            position: sticky; top: 0; z-index: 100;
            border-bottom: 2px solid var(--border-color);
        }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.75rem 1.5rem; font-weight: 600; font-size: 0.95rem;
            text-decoration: none; color: var(--text-main);
            border-radius: 0; cursor: pointer;
        }
        
        .btn-dark { 
            background: var(--text-main); 
            color: white; 
        }
        
        .btn-light { 
            background: white; 
        }

        /* Highlight Label */
        .label-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 1rem;
            background: white;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 999px;
            margin-bottom: 2rem;
        }

        /* Marquee */
        .marquee-wrapper {
            background: var(--text-main);
            color: white;
            padding: 1.25rem 0;
            border-top: 2px solid var(--border-color);
            border-bottom: 2px solid var(--border-color);
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            margin: 5rem 0;
            z-index: 10;
        }
        
        .marquee-content {
            display: inline-block;
            animation: marquee 25s linear infinite;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Feature Cards */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2.5rem;
            position: relative;
            z-index: 10;
        }

        .feature-card {
            background: white;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .feature-number {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        /* Custom Pulse Indicator */
        .indicator {
            display: inline-block;
            width: 8px; height: 8px;
            background: #111111;
            border-radius: 50%;
            margin-right: 10px;
            animation: blink 2s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="bg-logo-blur"></div>

    <!-- Nav -->
    <nav class="navbar">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <img src="/logo.png" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;">
            <span style="font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px;">Node Center</span>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-light structural-border structural-shadow">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light structural-border structural-shadow">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-dark structural-border structural-shadow">
                            Get Started
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero -->
    <main style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 7rem 2.5rem 4rem 2.5rem; text-align: center; min-height: 75vh; position: relative; z-index: 10;">
        
        <div class="label-pill structural-border structural-shadow">
            <span class="indicator"></span> System Operational
        </div>

        <h1 style="font-size: clamp(3rem, 7vw, 6rem); font-weight: 800; line-height: 1.05; margin-bottom: 1.75rem; max-width: 950px; letter-spacing: -1.5px;">
            Application monitoring,<br>
            engineered for scale.
        </h1>

        <p style="font-size: 1.15rem; font-weight: 500; color: var(--text-muted); max-width: 600px; line-height: 1.6; margin-bottom: 3.5rem;">
            Connect your infrastructure via a secure API. Track system resources, active users, and application errors from a centralized command interface.
        </p>

        <div style="display: flex; gap: 1.25rem; flex-wrap: wrap; justify-content: center;">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-dark structural-border structural-shadow" style="padding: 1rem 2.5rem;">
                    Access Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-dark structural-border structural-shadow" style="padding: 1rem 2.5rem;">
                    Deploy Monitoring
                </a>
                <a href="{{ route('login') }}" class="btn btn-light structural-border structural-shadow" style="padding: 1rem 2.5rem;">
                    Sign In
                </a>
            @endauth
        </div>
    </main>

    <!-- Marquee -->
    <div class="marquee-wrapper">
        <div class="marquee-content">
            REAL-TIME TELEMETRY / SECURE ARCHITECTURE / SEAMLESS INTEGRATION / ERROR TRACKING / RESOURCE MONITORING / PERFORMANCE METRICS / REAL-TIME TELEMETRY / SECURE ARCHITECTURE / SEAMLESS INTEGRATION / ERROR TRACKING / RESOURCE MONITORING / PERFORMANCE METRICS /
        </div>
    </div>

    <!-- Features -->
    <section style="padding: 2rem 0 8rem 0;">
        <div class="feature-grid">
            <div class="feature-card structural-border structural-shadow">
                <span class="feature-number">01 / SECURITY</span>
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; letter-spacing: -0.5px;">Cryptographic Auth</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6;">Token-based authentication ensures that only your verified servers have the authority to push metrics to the network.</p>
            </div>
            
            <div class="feature-card structural-border structural-shadow">
                <span class="feature-number">02 / TELEMETRY</span>
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; letter-spacing: -0.5px;">Live Dashboard</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6;">Aggregated metrics auto-refresh continuously. Maintain full visibility over CPU spikes, memory usage, and latency.</p>
            </div>

            <div class="feature-card structural-border structural-shadow">
                <span class="feature-number">03 / INTEGRATION</span>
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; letter-spacing: -0.5px;">Seamless Setup</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6;">Standardized code snippets for immediate deployment. Configure your endpoints and begin monitoring in minutes.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="padding: 4rem 2.5rem; border-top: 2px solid var(--border-color); background: white; text-align: center; font-weight: 500;">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 1.5rem;">
            <img src="/logo.png" alt="Logo" style="width: 64px; height: 64px; object-fit: contain; filter: drop-shadow(2px 2px 0 var(--border-color));">
            <span style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.5px;">Node Center</span>
        </div>
        <p style="color: var(--text-muted); font-size: 0.85rem;">&copy; {{ date('Y') }} Node Center Monitor. Engineered for reliability.</p>
    </footer>

</body>
</html>
