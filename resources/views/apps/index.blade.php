<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 1.2rem; font-weight: 700;">📦 My Applications</h2>
            <a href="{{ route('apps.create') }}" class="neo-btn neo-btn-primary neo-btn-sm">+ New App</a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="neo-alert neo-alert-success">✅ {{ session('success') }}</div>
    @endif

    @if(session('new_token'))
        <div x-data="{ showToken: true }" x-show="showToken" class="neo-alert neo-alert-token" style="position: relative;">
            <button @click="showToken = false" style="position: absolute; top: 0.75rem; right: 0.75rem; background: none; border: none; cursor: pointer; font-size: 1.1rem; color: #666;">✕</button>
            <h4 style="font-weight: 700; margin-bottom: 0.35rem;">🔑 API Token Generated!</h4>
            <p style="font-size: 0.8rem; color: #555; margin-bottom: 0.75rem;">Copy this token now. It will <strong style="color: #dc2626;">NOT</strong> be shown again.</p>
            <div class="neo-token-box" id="token-value">{{ session('new_token') }}</div>
            <button onclick="navigator.clipboard.writeText(document.getElementById('token-value').textContent.trim()); this.textContent='✅ Copied!'; setTimeout(() => this.textContent='📋 Copy Token', 2000);"
                class="neo-btn neo-btn-sky neo-btn-sm" style="margin-top: 0.75rem;">
                📋 Copy Token
            </button>
        </div>
    @endif

    @if($apps->isEmpty())
        <div class="neo-card" style="text-align: center; padding: 3rem;">
            <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">📭</div>
            <h3 style="font-weight: 700; margin-bottom: 0.35rem;">No applications yet</h3>
            <p style="color: #666; font-size: 0.85rem; margin-bottom: 1.25rem;">Register your first app to start monitoring</p>
            <a href="{{ route('apps.create') }}" class="neo-btn neo-btn-primary">+ New Application</a>
        </div>
    @else
        <div style="display: grid; gap: 0.75rem;">
            @foreach($apps as $app)
                <div x-data="{ confirmDelete: false, confirmToken: false }" class="neo-card" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.85rem; min-width: 0; flex: 1;">
                        <div style="width: 40px; height: 40px; min-width: 40px; border-radius: 0.65rem; background: var(--sky); border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; box-shadow: 2px 2px 0 var(--border);">
                            {{ strtoupper(substr($app->name, 0, 1)) }}
                        </div>
                        <div style="min-width: 0;">
                            @php
                                $m = $app->metrics->first();
                                $isOnline = $m && $m->created_at->diffInMinutes(now()) <= 5;
                            @endphp
                            <h4 style="font-weight: 700; font-size: 0.9rem;">{{ $app->name }}</h4>
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.15rem;">
                                <a href="{{ $app->url }}" target="_blank" style="font-size: 0.7rem; color: #888; text-decoration: none;">{{ $app->url }}</a>
                                <span style="font-size: 0.65rem; color: #bbb;">•</span>
                                <span style="font-size: 0.7rem; color: #888;">{{ $app->type ?? 'General' }}</span>
                                <span class="neo-badge {{ $isOnline ? 'neo-badge-online' : 'neo-badge-offline' }}" style="font-size: 0.55rem;">
                                    {{ $isOnline ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <!-- Edit Button -->
                        <a href="{{ route('apps.edit', $app->id) }}" class="neo-btn neo-btn-sky neo-btn-sm" style="text-decoration: none;">✏️</a>

                        <!-- Token Button -->
                        <button @click="confirmToken = true" class="neo-btn neo-btn-warning neo-btn-sm">🔑 Token</button>

                        <!-- Delete Button -->
                        <button @click="confirmDelete = true" class="neo-btn neo-btn-danger neo-btn-sm">🗑️</button>
                    </div>

                    <!-- Token Confirmation Modal -->
                    <template x-teleport="body">
                        <div x-show="confirmToken" class="neo-modal-overlay" @click.self="confirmToken = false">
                            <div class="neo-modal">
                                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">🔑 Generate API Token</h3>
                                <p style="font-size: 0.85rem; color: #555; margin-bottom: 1.25rem;">
                                    This will <strong>replace</strong> any existing token for <strong>{{ $app->name }}</strong>. Any integration using the old token will stop working.
                                </p>
                                <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                                    <button @click="confirmToken = false" class="neo-btn neo-btn-sm" style="background: #eee;">Cancel</button>
                                    <form method="POST" action="{{ route('apps.token', $app) }}">
                                        @csrf
                                        <button type="submit" class="neo-btn neo-btn-warning neo-btn-sm">🔑 Generate</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Delete Confirmation Modal -->
                    <template x-teleport="body">
                        <div x-show="confirmDelete" class="neo-modal-overlay" @click.self="confirmDelete = false">
                            <div class="neo-modal">
                                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">🗑️ Delete Application</h3>
                                <p style="font-size: 0.85rem; color: #555; margin-bottom: 1.25rem;">
                                    Are you sure you want to delete <strong>{{ $app->name }}</strong>? All metrics and tokens will be permanently removed. This action <strong style="color: #dc2626;">cannot be undone</strong>.
                                </p>
                                <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                                    <button @click="confirmDelete = false" class="neo-btn neo-btn-sm" style="background: #eee;">Cancel</button>
                                    <form method="POST" action="{{ route('apps.destroy', $app) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="neo-btn neo-btn-danger neo-btn-sm">🗑️ Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
