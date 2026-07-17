<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Node Center') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />
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
                --shadow: 4px 4px 0 #2D2D2D;
                --shadow-sm: 3px 3px 0 #2D2D2D;
            }
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Space Grotesk', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        </style>
    </head>
    <body>
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem; width: 100%;">
            <div style="margin-bottom: 2rem; text-align: center;">
                <a href="/" style="text-decoration: none;">
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--butter); border: 3px solid var(--border); border-radius: 1rem; padding: 0.75rem 1.25rem; box-shadow: var(--shadow); font-size: 1.25rem; font-weight: 700; color: var(--text);">
                        📡 Node Center
                    </div>
                </a>
            </div>
            <div style="width: 100%; max-width: 440px; background: white; border: 3px solid var(--border); border-radius: 1.25rem; padding: 2rem; box-shadow: 6px 6px 0 var(--border);">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
