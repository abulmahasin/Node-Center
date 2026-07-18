<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Node Center — Professional App Monitor</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --bg-color: #F4F4F0; /* Off-white beige */
            --text-main: #000000;
            --border-color: #000000;
            --primary: #FF5E5B; /* Red */
            --secondary: #00CECB; /* Cyan */
            --tertiary: #FFED66; /* Yellow */
            --quaternary: #C8B6FF; /* Purple */
        }

        body { 
            font-family: 'Space Grotesk', sans-serif; 
            background: var(--bg-color); 
            color: var(--text-main); 
            min-height: 100vh; 
            position: relative; 
            overflow-x: hidden; 
        }

        /* Bold Polka Dot Pattern */
        .bg-dots {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: radial-gradient(var(--border-color) 2px, transparent 2px);
            background-size: 32px 32px;
            opacity: 0.1;
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
            opacity: 0.05;
            filter: blur(10px);
            z-index: -1;
            pointer-events: none;
            animation: atmosphericPulse 15s ease-in-out infinite alternate;
        }

        @keyframes atmosphericPulse {
            0% { transform: translate(-50%, -50%) rotate(-5deg) scale(0.9); }
            100% { transform: translate(-50%, -50%) rotate(5deg) scale(1.1); }
        }

        /* Core Neo-Brutalism Classes */
        .neo-border { 
            border: 4px solid var(--border-color); 
        }
        
        .neo-shadow { 
            box-shadow: 8px 8px 0 var(--border-color); 
            transition: all 0.2s cubic-bezier(0.2, 0.8, 0.2, 1); 
        }
        
        .neo-shadow:hover { 
            transform: translate(-4px, -4px); 
            box-shadow: 12px 12px 0 var(--border-color); 
        }
        
        .neo-shadow:active { 
            transform: translate(4px, 4px); 
            box-shadow: 4px 4px 0 var(--border-color); 
        }

        /* Navbar */
        .navbar {
            display: flex; justify-content: space-between; align-items: center; 
            padding: 1.25rem 2.5rem; background: var(--bg-color); 
            position: sticky; top: 0; z-index: 100;
            border-bottom: 4px solid var(--border-color);
        }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.85rem 2rem; font-weight: 800; font-size: 1.05rem;
            text-transform: uppercase; letter-spacing: 1px;
            text-decoration: none; color: var(--text-main);
            border-radius: 0; cursor: pointer;
        }
        
        .btn-primary { background: var(--primary); color: #000; }
        .btn-secondary { background: var(--secondary); color: #000; }
        .btn-white { background: white; color: #000; }

        /* Highlight Label */
        .label-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1.5rem;
            background: var(--tertiary);
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 2.5rem;
            transform: rotate(-2deg);
        }

        /* Marquee */
        .marquee-wrapper {
            background: var(--text-main);
            color: var(--tertiary);
            padding: 1.5rem 0;
            border-top: 4px solid var(--border-color);
            border-bottom: 4px solid var(--border-color);
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            margin: 6rem 0;
            z-index: 10;
            transform: rotate(-1deg);
        }
        
        .marquee-content {
            display: inline-block;
            animation: marquee 20s linear infinite;
            font-weight: 900;
            font-size: 1.2rem;
            letter-spacing: 4px;
            text-transform: uppercase;
        }
        
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Feature Cards */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
            max-width: 1250px;
            margin: 0 auto;
            padding: 0 2.5rem;
            position: relative;
            z-index: 10;
        }

        .feature-card {
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .feature-number {
            font-size: 3.5rem;
            font-weight: 900;
            color: var(--text-main);
            line-height: 1;
            margin-bottom: -0.5rem;
        }

        /* Custom Pulse Indicator */
        .indicator {
            display: inline-block;
            width: 12px; height: 12px;
            background: #000;
            border-radius: 50%;
            margin-right: 12px;
            animation: blink 1.5s steps(2, start) infinite;
        }
        @keyframes blink {
            to { visibility: hidden; }
        }

        /* Decorative Elements */
        .highlight-text {
            background: var(--quaternary);
            padding: 0 0.5rem;
            display: inline-block;
            border: 4px solid var(--border-color);
            box-shadow: 6px 6px 0 var(--border-color);
            transform: rotate(2deg);
        }

        /* creator badge */
        .creator-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: white;
            padding: 0.6rem 1.8rem 0.6rem 1.5rem;
            border: 4px solid #000;
            box-shadow: 6px 6px 0 #000;
            font-weight: 700;
            font-size: 1rem;
            transform: rotate(1deg);
            margin-top: 2.5rem;
        }
        .creator-badge a {
            color: #000;
            text-decoration: none;
            font-weight: 800;
            border-bottom: 3px solid var(--secondary);
            transition: 0.2s;
        }
        .creator-badge a:hover {
            background: var(--secondary);
            padding: 0.1rem 0.3rem;
        }
        .creator-badge img {
            width: 28px;
            height: 28px;
            filter: drop-shadow(2px 2px 0 #000);
        }
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin: 1.5rem 0 1rem;
        }
        .footer-links a {
            font-weight: 700;
            color: #000;
            text-decoration: none;
            border-bottom: 4px solid var(--secondary);
            padding: 0.2rem 0.4rem;
            transition: 0.2s;
        }
        .footer-links a:hover {
            background: var(--secondary);
            border-color: #000;
        }
    </style>
</head>
<body>
    <div class="bg-dots"></div>
    <div class="bg-logo-blur"></div>

    <!-- Nav -->
    <nav class="navbar">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <img src="/logo.png" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;">
            <span style="font-size: 1.8rem; font-weight: 900; letter-spacing: -1px; text-transform: uppercase;">Node Center</span>
        </div>
        
        <div style="display: flex; gap: 1.5rem;">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary neo-border neo-shadow">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-white neo-border neo-shadow">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-secondary neo-border neo-shadow">
                            Get Started
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero -->
    <main style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 8rem 2.5rem 5rem 2.5rem; text-align: center; min-height: 80vh; position: relative; z-index: 10;">
        
        <div class="label-pill neo-border neo-shadow">
            <span class="indicator"></span> ALL SYSTEMS OPERATIONAL
        </div>

        <h1 style="font-size: clamp(3.5rem, 8vw, 7rem); font-weight: 900; line-height: 1; margin-bottom: 2rem; max-width: 1000px; text-transform: uppercase;">
            MONITOR APPS WITH <br>
            <span class="highlight-text">EXTREME</span> CLARITY.
        </h1>

        <p style="font-size: 1.35rem; font-weight: 600; color: var(--text-main); max-width: 650px; line-height: 1.6; margin-bottom: 4rem; border: 4px solid var(--border-color); padding: 1.5rem; background: white; box-shadow: 8px 8px 0 var(--border-color);">
            Connect your infrastructure via a secure API. Track system resources, active users, and application errors from a centralized, blazing-fast command interface.
        </p>

        <div style="display: flex; gap: 2rem; flex-wrap: wrap; justify-content: center;">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary neo-border neo-shadow" style="padding: 1.25rem 3rem; font-size: 1.25rem;">
                    Access Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary neo-border neo-shadow" style="padding: 1.25rem 3rem; font-size: 1.25rem;">
                    Deploy Monitoring
                </a>
                <a href="{{ route('login') }}" class="btn btn-white neo-border neo-shadow" style="padding: 1.25rem 3rem; font-size: 1.25rem;">
                    Sign In
                </a>
            @endauth
        </div>

        <!-- CREATOR BADGE (Abul Mahasin + LinkedIn + GitHub) -->
        <div class="creator-badge">
            <span>🛠️ Developed by</span>
            <a href="https://www.linkedin.com/in/abulmahasin/" target="_blank" rel="noopener noreferrer">
                Abul Mahasin
            </a>
            <span style="opacity:0.4; margin:0 0.2rem;">|</span>
            <a href="https://github.com/abulmahasin" target="_blank" rel="noopener noreferrer">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/github.svg" alt="GitHub" style="width:28px; height:28px; filter: drop-shadow(2px 2px 0 #000); vertical-align: middle;">
                GitHub
            </a>
        </div>
    </main>

    <!-- Marquee -->
    <div class="marquee-wrapper">
        <div class="marquee-content">
            REAL-TIME TELEMETRY / SECURE ARCHITECTURE / SEAMLESS INTEGRATION / ERROR TRACKING / RESOURCE MONITORING / PERFORMANCE METRICS / REAL-TIME TELEMETRY / SECURE ARCHITECTURE / SEAMLESS INTEGRATION / ERROR TRACKING / RESOURCE MONITORING / PERFORMANCE METRICS /
        </div>
    </div>

    <!-- Features -->
    <section style="padding: 4rem 0 10rem 0;">
        <div class="feature-grid">
            <div class="feature-card neo-border neo-shadow" style="background: var(--tertiary);">
                <div class="feature-number">01</div>
                <h3 style="font-size: 2rem; font-weight: 900; margin-bottom: 0.5rem; text-transform: uppercase;">Cryptographic Auth</h3>
                <p style="font-size: 1.1rem; font-weight: 600; color: #000; line-height: 1.6;">Token-based authentication ensures that only your verified servers have the authority to push metrics.</p>
            </div>
            
            <div class="feature-card neo-border neo-shadow" style="background: var(--secondary);">
                <div class="feature-number">02</div>
                <h3 style="font-size: 2rem; font-weight: 900; margin-bottom: 0.5rem; text-transform: uppercase;">Live Dashboard</h3>
                <p style="font-size: 1.1rem; font-weight: 600; color: #000; line-height: 1.6;">Aggregated metrics auto-refresh continuously. Maintain full visibility over CPU spikes and latency.</p>
            </div>

            <div class="feature-card neo-border neo-shadow" style="background: var(--quaternary);">
                <div class="feature-number">03</div>
                <h3 style="font-size: 2rem; font-weight: 900; margin-bottom: 0.5rem; text-transform: uppercase;">Seamless Setup</h3>
                <p style="font-size: 1.1rem; font-weight: 600; color: #000; line-height: 1.6;">Standardized code snippets for immediate deployment. Configure your endpoints in minutes.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="padding: 5rem 2.5rem; border-top: 4px solid var(--border-color); background: var(--bg-color); text-align: center; font-weight: 700;">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
            <img src="/logo.png" alt="Logo" style="width: 64px; height: 64px; object-fit: contain; filter: drop-shadow(4px 4px 0 var(--border-color));">
            <span style="font-size: 2rem; font-weight: 900; letter-spacing: -1px; text-transform: uppercase;">Node Center</span>
        </div>

        <!-- FOOTER LINKS: LinkedIn & GitHub (Abul Mahasin) -->
        <div class="footer-links">
            <a href="https://www.linkedin.com/in/abulmahasin/" target="_blank" rel="noopener noreferrer">
                 LinkedIn / Abul Mahasin
            </a>
            <a href="https://github.com/abulmahasin" target="_blank" rel="noopener noreferrer">
                 GitHub / abulmahasin
            </a>
        </div>

        <p style="color: var(--text-main); font-size: 1rem; font-weight: 600; margin-top: 1.5rem;">
            &copy; {{ date('Y') }} Node Center Monitor. Engineered for reliability.
        </p>
        <p style="font-size: 0.9rem; margin-top: 0.5rem; font-weight: 500; opacity: 0.7;">
            <span style="background: var(--tertiary); padding: 0.1rem 0.8rem; border: 2px solid #000;">Developed by oleh Abul Mahasin</span>
        </p>
    </footer>

</body>
</html>