<x-guest-layout>
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; color: #1A1A2E;">Welcome back! 👋</h2>
    <p style="font-size: 0.85rem; color: #666; margin-bottom: 1.5rem;">Log in to your monitoring dashboard</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="margin-bottom: 1.25rem;">
            <label for="email" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1A1A2E;">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                style="width: 100%; padding: 0.75rem 1rem; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-size: 0.9rem; font-family: inherit; background: #FFF8F0; box-shadow: 3px 3px 0 #2D2D2D; outline: none; transition: all 0.2s;"
                onfocus="this.style.boxShadow='3px 3px 0 #6C63FF'; this.style.borderColor='#6C63FF'"
                onblur="this.style.boxShadow='3px 3px 0 #2D2D2D'; this.style.borderColor='#2D2D2D'"
                placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label for="password" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1A1A2E;">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                style="width: 100%; padding: 0.75rem 1rem; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-size: 0.9rem; font-family: inherit; background: #FFF8F0; box-shadow: 3px 3px 0 #2D2D2D; outline: none; transition: all 0.2s;"
                onfocus="this.style.boxShadow='3px 3px 0 #6C63FF'; this.style.borderColor='#6C63FF'"
                onblur="this.style.boxShadow='3px 3px 0 #2D2D2D'; this.style.borderColor='#2D2D2D'"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input id="remember_me" type="checkbox" name="remember"
                style="width: 18px; height: 18px; border: 2px solid #2D2D2D; border-radius: 4px; accent-color: #6C63FF;">
            <label for="remember_me" style="font-size: 0.85rem; color: #444;">Remember me</label>
        </div>

        <button type="submit"
            style="width: 100%; padding: 0.85rem; background: #E8DAFB; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-size: 1rem; font-weight: 700; font-family: inherit; cursor: pointer; box-shadow: 4px 4px 0 #2D2D2D; transition: all 0.15s; color: #1A1A2E;"
            onmousedown="this.style.transform='translate(2px,2px)'; this.style.boxShadow='2px 2px 0 #2D2D2D'"
            onmouseup="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'"
            onmouseleave="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'">
            Log in →
        </button>

        <div style="display: flex; justify-content: space-between; margin-top: 1.25rem; font-size: 0.8rem;">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="color: #6C63FF; text-decoration: none; font-weight: 600;">Forgot password?</a>
            @endif
            <a href="{{ route('register') }}" style="color: #6C63FF; text-decoration: none; font-weight: 600;">Create account →</a>
        </div>
    </form>
</x-guest-layout>
