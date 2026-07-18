<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\AlertLog;

class SettingController extends Controller
{
    public function index()
    {
        $telegramToken = Setting::get('telegram_bot_token');
        $telegramChatId = Setting::get('telegram_default_chat_id');
        $alertsEnabled = Setting::get('alerts_enabled', 'true');

        $alertLogs = AlertLog::with('app')
            ->latest()
            ->paginate(20);

        return view('settings.index', compact('telegramToken', 'telegramChatId', 'alertsEnabled', 'alertLogs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'telegram_bot_token' => 'nullable|string|max:255',
            'telegram_default_chat_id' => 'nullable|string|max:100',
            'alerts_enabled' => 'nullable|string',
        ]);

        Setting::set('telegram_bot_token', $request->telegram_bot_token);
        Setting::set('telegram_default_chat_id', $request->telegram_default_chat_id);
        Setting::set('alerts_enabled', $request->has('alerts_enabled') ? 'true' : 'false');

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }

    public function testTelegram()
    {
        $token = Setting::get('telegram_bot_token');
        $chatId = Setting::get('telegram_default_chat_id');

        if (!$token || !$chatId) {
            return back()->with('error', 'Please fill in Telegram Bot Token and Chat ID first.');
        }

        try {
            $response = \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => "✅ *Node Center Test*\n\nKoneksi Telegram berhasil!\nBot ini akan mengirim pesan jika ada aplikasi yang DOWN.\n\n🕐 " . now()->format('Y-m-d H:i:s'),
                'parse_mode' => 'Markdown',
            ]);

            if ($response->successful() && $response->json('ok')) {
                return back()->with('success', '✅ Test message sent successfully! Check your Telegram.');
            } else {
                $err = $response->json('description') ?? 'Unknown error';
                return back()->with('error', "❌ Telegram API error: {$err}");
            }
        } catch (\Exception $e) {
            return back()->with('error', '❌ Connection failed: ' . $e->getMessage());
        }
    }
}
