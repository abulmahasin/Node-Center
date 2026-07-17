<x-guest-layout>
    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; color: #1A1A2E;">Create Account 🚀</h2>
    <p style="font-size: 0.85rem; color: #666; margin-bottom: 1.5rem;">Get started with app monitoring</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div style="margin-bottom: 1rem;">
            <label for="name" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem;">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                style="width: 100%; padding: 0.75rem 1rem; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-size: 0.9rem; font-family: inherit; background: #FFF8F0; box-shadow: 3px 3px 0 #2D2D2D; outline: none; transition: all 0.2s;"
                onfocus="this.style.boxShadow='3px 3px 0 #6C63FF'; this.style.borderColor='#6C63FF'"
                onblur="this.style.boxShadow='3px 3px 0 #2D2D2D'; this.style.borderColor='#2D2D2D'"
                placeholder="Your name">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="email" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem;">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                style="width: 100%; padding: 0.75rem 1rem; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-size: 0.9rem; font-family: inherit; background: #FFF8F0; box-shadow: 3px 3px 0 #2D2D2D; outline: none; transition: all 0.2s;"
                onfocus="this.style.boxShadow='3px 3px 0 #6C63FF'; this.style.borderColor='#6C63FF'"
                onblur="this.style.boxShadow='3px 3px 0 #2D2D2D'; this.style.borderColor='#2D2D2D'"
                placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="password" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem;">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                style="width: 100%; padding: 0.75rem 1rem; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-size: 0.9rem; font-family: inherit; background: #FFF8F0; box-shadow: 3px 3px 0 #2D2D2D; outline: none; transition: all 0.2s;"
                onfocus="this.style.boxShadow='3px 3px 0 #6C63FF'; this.style.borderColor='#6C63FF'"
                onblur="this.style.boxShadow='3px 3px 0 #2D2D2D'; this.style.borderColor='#2D2D2D'"
                placeholder="Minimum 8 characters">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="password_confirmation" style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem;">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                style="width: 100%; padding: 0.75rem 1rem; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-size: 0.9rem; font-family: inherit; background: #FFF8F0; box-shadow: 3px 3px 0 #2D2D2D; outline: none; transition: all 0.2s;"
                onfocus="this.style.boxShadow='3px 3px 0 #6C63FF'; this.style.borderColor='#6C63FF'"
                onblur="this.style.boxShadow='3px 3px 0 #2D2D2D'; this.style.borderColor='#2D2D2D'"
                placeholder="Repeat password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
            style="width: 100%; padding: 0.85rem; background: #C1F0DB; border: 3px solid #2D2D2D; border-radius: 0.75rem; font-size: 1rem; font-weight: 700; font-family: inherit; cursor: pointer; box-shadow: 4px 4px 0 #2D2D2D; transition: all 0.15s; color: #1A1A2E;"
            onmousedown="this.style.transform='translate(2px,2px)'; this.style.boxShadow='2px 2px 0 #2D2D2D'"
            onmouseup="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'"
            onmouseleave="this.style.transform=''; this.style.boxShadow='4px 4px 0 #2D2D2D'">
            Create Account →
        </button>

        <p style="text-align: center; margin-top: 1.25rem; font-size: 0.8rem; color: #666;">
            Already have an account? <a href="{{ route('login') }}" style="color: #6C63FF; font-weight: 600; text-decoration: none;">Log in →</a>
        </p>
    </form>
</x-guest-layout>
