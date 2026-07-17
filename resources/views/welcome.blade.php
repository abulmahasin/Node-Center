<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Node Center — App Monitor</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Space Grotesk', sans-serif; background: #FFF8F0; color: #1A1A2E; min-height: 100vh; }
        .neo-shadow { box-shadow: 5px 5px 0 #2D2D2D; }
        .neo-shadow-sm { box-shadow: 3px 3px 0 #2D2D2D; }
    </style>
</head>
<body>
    <!-- Nav -->
    <nav style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 2rem; border-bottom: 3px solid #2D2D2D; background: white;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span style="background: #FFF3B0; border: 2px solid #2D2D2D; border-radius: 0.5rem; padding: 0.3rem 0.6rem; font-weight: 700; box-shadow: 2px 2px 0 #2D2D2D;">📡</span>
            <span style="font-size: 1.1rem; font-weight: 700;">Node Center</span>
        </div>
        @if (Route::has('login'))
            <div style="display: flex; gap: 0.75rem;">
                @auth
                    <a href="{{ url('/dashboard') }}" style="padding: 0.55rem 1.25rem; background: #E8DAFB; border: 2px solid #2D2D2D; border-radius: 0.5rem; font-weight: 700; font-size: 0.85rem; text-decoration: none; color: #1A1A2E; box-shadow: 3px 3px 0 #2D2D2D;">Dashboard →</a>
                @else
                    <a href="{{ route('login') }}" style="padding: 0.55rem 1.25rem; border: 2px solid #2D2D2D; border-radius: 0.5rem; font-weight: 600; font-size: 0.85rem; text-decoration: none; color: #1A1A2E; background: white;">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" style="padding: 0.55rem 1.25rem; background: #C1F0DB; border: 2px solid #2D2D2D; border-radius: 0.5rem; font-weight: 700; font-size: 0.85rem; text-decoration: none; color: #1A1A2E; box-shadow: 3px 3px 0 #2D2D2D;">Get Started</a>
                    @endif
                @endauth
            </div>
        @endif
    </nav>

    <!-- Hero -->
    <main style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 2rem; text-align: center; min-height: 80vh;">
        <div style="background: #E8DAFB; border: 2px solid #2D2D2D; border-radius: 999px; padding: 0.4rem 1.25rem; font-size: 0.75rem; font-weight: 700; margin-bottom: 2rem; box-shadow: 2px 2px 0 #2D2D2D;">
            🔥 Professional App Monitoring
        </div>

        <h1 style="font-size: clamp(2.5rem, 6vw, 4rem); font-weight: 800; line-height: 1.1; margin-bottom: 1.25rem; max-width: 700px;">
            Monitor your apps in<br>
            <span style="background: #C1F0DB; border: 3px solid #2D2D2D; border-radius: 0.5rem; padding: 0.1rem 0.6rem; box-shadow: 3px 3px 0 #2D2D2D; display: inline-block; margin-top: 0.35rem;">real-time</span>
        </h1>

        <p style="font-size: 1.05rem; color: #555; max-width: 520px; line-height: 1.7; margin-bottom: 2.5rem;">
            Connect via a secure API. Track CPU, memory, users, and errors from one beautiful command center.
        </p>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
            @auth
                <a href="{{ url('/dashboard') }}" style="padding: 0.85rem 2rem; background: #E8DAFB; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; text-decoration: none; color: #1A1A2E; box-shadow: 4px 4px 0 #2D2D2D; transition: all 0.15s;"
                    onmousedown="this.style.transform='translate(2px,2px)'; this.style.boxShadow='2px 2px 0 #2D2D2D'"
                    onmouseup="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'"
                    onmouseleave="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'">
                    Open Dashboard →
                </a>
            @else
                <a href="{{ route('register') }}" style="padding: 0.85rem 2rem; background: #C1F0DB; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; text-decoration: none; color: #1A1A2E; box-shadow: 4px 4px 0 #2D2D2D; transition: all 0.15s;"
                    onmousedown="this.style.transform='translate(2px,2px)'; this.style.boxShadow='2px 2px 0 #2D2D2D'"
                    onmouseup="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'"
                    onmouseleave="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'">
                    Start Monitoring →
                </a>
                <a href="{{ route('login') }}" style="padding: 0.85rem 2rem; background: white; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; text-decoration: none; color: #1A1A2E; box-shadow: 4px 4px 0 #2D2D2D; transition: all 0.15s;"
                    onmousedown="this.style.transform='translate(2px,2px)'; this.style.boxShadow='2px 2px 0 #2D2D2D'"
                    onmouseup="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'"
                    onmouseleave="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'">
                    Log in
                </a>
            @endauth
        </div>

        <!-- Feature Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-top: 4rem; max-width: 800px; width: 100%;">
            <div style="background: #BDE0FE; border: 3px solid #2D2D2D; border-radius: 1rem; padding: 1.5rem; box-shadow: 4px 4px 0 #2D2D2D; text-align: left;">
                <div style="font-size: 1.75rem; margin-bottom: 0.75rem;">🔒</div>
                <h3 style="font-weight: 700; margin-bottom: 0.35rem;">Secure API</h3>
                <p style="font-size: 0.8rem; color: #333; line-height: 1.5;">Token-based authentication with Laravel Sanctum.</p>
            </div>
            <div style="background: #FFD6CC; border: 3px solid #2D2D2D; border-radius: 1rem; padding: 1.5rem; box-shadow: 4px 4px 0 #2D2D2D; text-align: left;">
                <div style="font-size: 1.75rem; margin-bottom: 0.75rem;">📊</div>
                <h3 style="font-weight: 700; margin-bottom: 0.35rem;">Live Dashboard</h3>
                <p style="font-size: 0.8rem; color: #333; line-height: 1.5;">Auto-refreshing metrics every 15 seconds.</p>
            </div>
            <div style="background: #FFF3B0; border: 3px solid #2D2D2D; border-radius: 1rem; padding: 1.5rem; box-shadow: 4px 4px 0 #2D2D2D; text-align: left;">
                <div style="font-size: 1.75rem; margin-bottom: 0.75rem;">📖</div>
                <h3 style="font-weight: 700; margin-bottom: 0.35rem;">Easy Integration</h3>
                <p style="font-size: 0.8rem; color: #333; line-height: 1.5;">Copy-paste code examples for Laravel, cURL & more.</p>
            </div>
        </div>
    </main>

    <footer style="text-align: center; padding: 1.5rem; border-top: 3px solid #2D2D2D; background: white; font-size: 0.8rem; color: #888;">
        &copy; {{ date('Y') }} Node Center Monitor · Built for professionals
    </footer>
</body>
</html>
