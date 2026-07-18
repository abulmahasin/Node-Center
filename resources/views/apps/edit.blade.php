<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size: 1.2rem; font-weight: 700;">✏️ Edit Application</h2>
    </x-slot>

    <div style="max-width: 520px;">
        <div class="neo-card">
            <form method="POST" action="{{ route('apps.update', $app->id) }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1.25rem;">
                    <label for="name" class="neo-label">Application Name</label>
                    <input type="text" name="name" id="name" required class="neo-input" placeholder="e.g. SISMA-AKA" value="{{ old('name', $app->name) }}">
                    @error('name') <p style="color: #dc2626; font-size: 0.75rem; margin-top: 0.3rem; font-weight: 600;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="url" class="neo-label">Application URL</label>
                    <input type="url" name="url" id="url" required class="neo-input" placeholder="e.g. https://sisma-aka.al-kautsar.sch.id" value="{{ old('url', $app->url) }}">
                    @error('url') <p style="color: #dc2626; font-size: 0.75rem; margin-top: 0.3rem; font-weight: 600;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="type" class="neo-label">Type / Framework <span style="color: #aaa; font-weight: 400;">(Optional)</span></label>
                    <input type="text" name="type" id="type" class="neo-input" placeholder="e.g. Laravel, NextJS, Flask" value="{{ old('type', $app->type) }}">
                    @error('type') <p style="color: #dc2626; font-size: 0.75rem; margin-top: 0.3rem; font-weight: 600;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom: 1.25rem; border-top: 2px solid var(--border); padding-top: 1rem;">
                    <h3 style="font-weight: 700; margin-bottom: 0.75rem;">🔔 Smart Alerts</h3>
                    
                    <label for="telegram_chat_id" class="neo-label">Telegram Chat ID <span style="color: #aaa; font-weight: 400;">(Optional)</span></label>
                    <input type="text" name="telegram_chat_id" id="telegram_chat_id" class="neo-input" placeholder="e.g. 123456789" value="{{ old('telegram_chat_id', $app->telegram_chat_id) }}">
                    <p style="font-size: 0.75rem; color: #666; margin-top: 0.25rem;">Bot will send message here if app goes down.</p>
                </div>

                <div style="margin-bottom: 1.75rem;">
                    <label for="alert_email" class="neo-label">Alert Email <span style="color: #aaa; font-weight: 400;">(Optional)</span></label>
                    <input type="email" name="alert_email" id="alert_email" class="neo-input" placeholder="e.g. admin@example.com" value="{{ old('alert_email', $app->alert_email) }}">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <a href="{{ route('apps.index') }}" class="neo-btn neo-btn-sm" style="background: #eee;">Cancel</a>
                    <button type="submit" class="neo-btn neo-btn-success">✅ Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
