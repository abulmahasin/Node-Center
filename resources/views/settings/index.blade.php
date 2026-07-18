<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 1.2rem; font-weight: 700;">⚙️ Settings & Alert Notifications</h2>
        </div>
    </x-slot>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="neo-card" style="background: var(--mint); border-color: #16a34a; margin-bottom: 1.25rem; padding: 0.75rem 1rem;">
            <span style="font-weight: 700; color: #166534;">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="neo-card" style="background: var(--pink); border-color: #dc2626; margin-bottom: 1.25rem; padding: 0.75rem 1rem;">
            <span style="font-weight: 700; color: #9f1239;">{{ session('error') }}</span>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- LEFT: Telegram Configuration -->
        <div>
            <div class="neo-card">
                <h3 style="font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--border); padding-bottom: 0.5rem;">
                    📱 Konfigurasi Telegram Bot
                </h3>

                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf

                    <!-- Toggle -->
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; padding: 0.75rem; background: {{ $alertsEnabled === 'true' ? 'var(--mint)' : '#fee2e2' }}; border-radius: 8px; border: 2px solid var(--border);">
                        <input type="checkbox" name="alerts_enabled" id="alerts_enabled" {{ $alertsEnabled === 'true' ? 'checked' : '' }} style="width: 1.2rem; height: 1.2rem;">
                        <label for="alerts_enabled" style="font-weight: 700; font-size: 0.85rem;">
                            {{ $alertsEnabled === 'true' ? '🔔 Alert Notifications AKTIF' : '🔕 Alert Notifications NONAKTIF' }}
                        </label>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label for="telegram_bot_token" class="neo-label">🤖 Bot Token</label>
                        <input type="text" name="telegram_bot_token" id="telegram_bot_token" class="neo-input" placeholder="1234567890:ABCdefGhIJKlmnoPQRSTuvwXYZ" value="{{ $telegramToken }}">
                        <p style="font-size: 0.7rem; color: #888; margin-top: 0.25rem;">Dapatkan dari @BotFather di Telegram</p>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="telegram_default_chat_id" class="neo-label">💬 Chat ID</label>
                        <input type="text" name="telegram_default_chat_id" id="telegram_default_chat_id" class="neo-input" placeholder="123456789 atau -100123456789" value="{{ $telegramChatId }}">
                        <p style="font-size: 0.7rem; color: #888; margin-top: 0.25rem;">Bisa personal ID atau group ID</p>
                    </div>

                    <div style="display: flex; gap: 0.75rem;">
                        <button type="submit" class="neo-btn neo-btn-success" style="flex: 1;">💾 Simpan Pengaturan</button>
                    </div>
                </form>

                <!-- Test Button -->
                <form method="POST" action="{{ route('settings.test-telegram') }}" style="margin-top: 0.75rem;">
                    @csrf
                    <button type="submit" class="neo-btn neo-btn-sky" style="width: 100%;">📤 Kirim Pesan Test ke Telegram</button>
                </form>
            </div>
        </div>

        <!-- RIGHT: Tutorial -->
        <div>
            <div class="neo-card">
                <h3 style="font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--border); padding-bottom: 0.5rem;">
                    📖 Cara Mendapatkan Bot Token & Chat ID
                </h3>

                <div style="font-size: 0.82rem; line-height: 1.7;">
                    <div style="margin-bottom: 1rem; padding: 0.75rem; background: var(--lavender); border-radius: 8px; border: 2px solid var(--border);">
                        <strong>Langkah 1: Buat Bot Telegram</strong>
                        <ol style="margin: 0.5rem 0 0 1.25rem; padding: 0;">
                            <li>Buka Telegram, cari <strong>@BotFather</strong></li>
                            <li>Ketik <code>/newbot</code></li>
                            <li>Beri nama bot Anda (contoh: "Node Center Alert")</li>
                            <li>BotFather akan memberikan <strong>Bot Token</strong></li>
                            <li>Salin token dan tempel di kolom <strong>Bot Token</strong> di sebelah kiri</li>
                        </ol>
                    </div>

                    <div style="margin-bottom: 1rem; padding: 0.75rem; background: var(--butter); border-radius: 8px; border: 2px solid var(--border);">
                        <strong>Langkah 2: Dapatkan Chat ID</strong>
                        <ol style="margin: 0.5rem 0 0 1.25rem; padding: 0;">
                            <li>Buka <strong>Telegram</strong>, cari nama bot yang baru Anda buat</li>
                            <li>Klik <strong>Start</strong> lalu kirim pesan apa saja (misal: "halo")</li>
                            <li>Setelah itu, klik tombol di bawah ini untuk membuka halaman Chat ID:</li>
                        </ol>

                        @if($telegramToken)
                            <a href="https://api.telegram.org/bot{{ $telegramToken }}/getUpdates" target="_blank" class="neo-btn neo-btn-sm" style="margin-top: 0.5rem; display: inline-block; background: #1a1a2e; color: #a6e3a1; text-decoration: none; font-size: 0.75rem;">
                                🔗 Buka getUpdates (klik setelah kirim pesan ke bot)
                            </a>
                        @else
                            <p style="margin-top: 0.5rem; font-size: 0.75rem; color: #9f1239; font-weight: 600;">
                                ⚠️ Simpan Bot Token terlebih dahulu, lalu link otomatis muncul di sini.
                            </p>
                        @endif

                        <div style="margin-top: 0.5rem; padding: 0.5rem; background: white; border-radius: 6px; border: 1px solid var(--border);">
                            <p style="font-size: 0.75rem; margin: 0; color: #555;"><strong>📌 Cara membaca hasilnya:</strong></p>
                            <p style="font-size: 0.72rem; margin: 0.25rem 0 0; color: #666;">Di halaman yang terbuka, cari bagian seperti ini:</p>
                            <code style="font-size: 0.68rem; background: #1a1a2e; color: #a6e3a1; padding: 0.35rem 0.5rem; border-radius: 4px; display: block; margin-top: 0.35rem; line-height: 1.6;">"chat":{"id":<strong style="color: #f9e2af;">123456789</strong>,"first_name":"Abul"}</code>
                            <p style="font-size: 0.72rem; margin: 0.35rem 0 0; color: #555;">Angka <strong>123456789</strong> itulah <strong>Chat ID</strong> Anda. Salin dan tempel di kolom Chat ID di sebelah kiri.</p>
                        </div>
                    </div>

                    <div style="padding: 0.75rem; background: var(--mint); border-radius: 8px; border: 2px solid var(--border);">
                        <strong>💡 Tips: Untuk Grup Telegram</strong>
                        <ol style="margin: 0.35rem 0 0 1.25rem; padding: 0; font-size: 0.8rem;">
                            <li>Tambahkan bot Anda ke dalam <strong>Grup Telegram</strong></li>
                            <li>Kirim pesan apa saja di grup tersebut</li>
                            <li>Buka kembali link <strong>getUpdates</strong> di atas</li>
                            <li>Chat ID grup biasanya dimulai dengan <code>-100</code>. Contoh: <code>-1001234567890</code></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Log Table -->
    <div class="neo-card" style="margin-top: 1.5rem;">
        <h3 style="font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--border); padding-bottom: 0.5rem;">
            📋 Riwayat Alert Notifications
        </h3>

        @if($alertLogs->isEmpty())
            <div style="text-align: center; padding: 2.5rem; color: #888;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔕</div>
                <p style="font-weight: 600;">Belum ada notifikasi yang dikirim</p>
                <p style="font-size: 0.8rem;">Alert akan muncul di sini ketika aplikasi Anda DOWN atau kembali ONLINE</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.82rem; text-align: left;">
                    <thead>
                        <tr style="background: var(--bg); border-bottom: 2px solid var(--border);">
                            <th style="padding: 0.65rem;">Waktu</th>
                            <th style="padding: 0.65rem;">Aplikasi</th>
                            <th style="padding: 0.65rem;">Tipe</th>
                            <th style="padding: 0.65rem;">Channel</th>
                            <th style="padding: 0.65rem;">Status</th>
                            <th style="padding: 0.65rem;">Pesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alertLogs as $log)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.65rem; white-space: nowrap; font-family: monospace; font-size: 0.75rem;">
                                    {{ $log->created_at->format('d M H:i') }}
                                </td>
                                <td style="padding: 0.65rem; font-weight: 700;">
                                    {{ $log->app->name ?? 'Deleted' }}
                                </td>
                                <td style="padding: 0.65rem;">
                                    @if($log->type === 'down')
                                        <span class="neo-badge" style="background: var(--pink); color: #9f1239;">🚨 DOWN</span>
                                    @elseif($log->type === 'recovery')
                                        <span class="neo-badge" style="background: var(--mint); color: #166534;">✅ RECOVERY</span>
                                    @elseif($log->type === 'ssl_expiring')
                                        <span class="neo-badge" style="background: var(--butter); color: #854d0e;">🔒 SSL</span>
                                    @else
                                        <span class="neo-badge" style="background: var(--card-alt);">{{ $log->type }}</span>
                                    @endif
                                </td>
                                <td style="padding: 0.65rem;">
                                    @if($log->channel === 'telegram')
                                        <span style="font-weight: 600;">📱 Telegram</span>
                                    @else
                                        <span style="font-weight: 600;">📧 Email</span>
                                    @endif
                                </td>
                                <td style="padding: 0.65rem;">
                                    @if($log->status === 'sent')
                                        <span class="neo-badge" style="background: var(--mint); color: #166534; font-size: 0.7rem;">✅ Sent</span>
                                    @else
                                        <span class="neo-badge" style="background: var(--pink); color: #9f1239; font-size: 0.7rem;" title="{{ $log->error }}">❌ Failed</span>
                                    @endif
                                </td>
                                <td style="padding: 0.65rem; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.75rem; color: #555;">
                                    {{ Str::limit(strip_tags($log->message), 80) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{ $alertLogs->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
