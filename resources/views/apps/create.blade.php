<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.2rem; font-weight: 700;">➕ Register New Application</h2>
    </x-slot>

    <div style="max-width: 520px;">
        <div class="neo-card">
            <form method="POST" action="{{ route('apps.store') }}">
                @csrf

                <div style="margin-bottom: 1.25rem;">
                    <label for="name" class="neo-label">Application Name</label>
                    <input type="text" name="name" id="name" required class="neo-input" placeholder="e.g. SISMA-AKA" value="{{ old('name') }}">
                    @error('name') <p style="color: #dc2626; font-size: 0.75rem; margin-top: 0.3rem; font-weight: 600;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="url" class="neo-label">Application URL</label>
                    <input type="url" name="url" id="url" required class="neo-input" placeholder="e.g. https://sisma-aka.al-kautsar.sch.id" value="{{ old('url') }}">
                    @error('url') <p style="color: #dc2626; font-size: 0.75rem; margin-top: 0.3rem; font-weight: 600;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom: 1.75rem;">
                    <label for="type" class="neo-label">Type / Framework <span style="color: #aaa; font-weight: 400;">(Optional)</span></label>
                    <input type="text" name="type" id="type" class="neo-input" placeholder="e.g. Laravel, NextJS, Flask" value="{{ old('type') }}">
                    @error('type') <p style="color: #dc2626; font-size: 0.75rem; margin-top: 0.3rem; font-weight: 600;">{{ $message }}</p> @enderror
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <a href="{{ route('apps.index') }}" class="neo-btn neo-btn-sm" style="background: #eee;">Cancel</a>
                    <button type="submit" class="neo-btn neo-btn-success">✅ Register App</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
