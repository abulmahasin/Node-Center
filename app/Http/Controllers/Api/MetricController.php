<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\AlertLog;

class MetricController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        if (!$accessToken || !$accessToken->tokenable || get_class($accessToken->tokenable) !== \App\Models\MonitoredApp::class) {
            return response()->json(['message' => 'Invalid or expired token.'], 401);
        }

        $app = $accessToken->tokenable;

        $safe = $request->only([
            'response_time',
            'cpu_usage',
            'memory_usage',
            'active_users',
            'error_rate',
            'error_count',
            'db_latency',
            'cache_latency',
        ]);

        Log::info('MetricController::store incoming metrics', [
            'app_id' => $app->id ?? null,
            'app_name' => $app->name ?? null,
            'metrics' => $safe,
            'ip' => $request->ip(),
        ]);

        // Also log full payload (sanitized): mask common sensitive keys
        try {
            $raw = $request->all();
            $sensitiveKeys = ['password', 'token', 'api_key', 'apiKey', 'authorization'];
            $sanitized = $raw;
            foreach ($sensitiveKeys as $k) {
                if (array_key_exists($k, $sanitized)) {
                    $sanitized[$k] = '***masked***';
                }
            }
            Log::info('MetricController::store raw payload (sanitized)', [
                'app_id' => $app->id ?? null,
                'payload' => $sanitized,
            ]);
        } catch (\Throwable $e) {
            // ignore logging errors
        }

        $validated = $request->validate([
            'cpu_usage' => 'required|numeric',
            'memory_usage' => 'required|numeric',
            'active_users' => 'required|integer',
            'response_time' => 'required|numeric',
            'error_rate' => 'required|numeric',
            'db_latency' => 'nullable|numeric',
            'cache_latency' => 'nullable|numeric',
            'disk_usage' => 'nullable|numeric',
            'pending_jobs' => 'nullable|integer',
            'failed_jobs' => 'nullable|integer',
            'error_count' => 'nullable|integer',
            'php_version' => 'nullable|string',
            'laravel_version' => 'nullable|string',
            'app_env' => 'nullable|string',
            'error_details' => 'nullable|array',
            'queue_details' => 'nullable|array',
            'schedule_details' => 'nullable|array',
            'slow_queries' => 'nullable|array',
            'security_warnings' => 'nullable|array',
        ]);

        // Prefer sender's summary if provided (more authoritative)
        $summary = $request->input('response_metrics_summary', null);
        if (is_array($summary)) {
            $preferred = isset($summary['recent_average_ms']) ? (float) $summary['recent_average_ms'] : (isset($summary['average_ms']) ? (float) $summary['average_ms'] : null);
            if ($preferred !== null && $preferred > 0) {
                if (isset($validated['response_time']) && abs($validated['response_time'] - $preferred) > 0.1) {
                    Log::info('MetricController::store overriding response_time with sender summary', ['app_id' => $app->id, 'original' => $validated['response_time'], 'preferred' => $preferred]);
                }
                $validated['response_time'] = $preferred;
            }
        }

        $app->update(['status' => 'online']);
        $metric = $app->metrics()->create($validated);

        // Detect suspicious constant response_time values (e.g. many 120ms entries)
        $rt = isset($validated['response_time']) ? (float) $validated['response_time'] : null;
        if ($rt !== null && abs($rt - 120) < 0.001) {
            Log::warning('MetricController::store detected response_time == 120', [
                'app_id' => $app->id,
                'response_time' => $rt,
                'metrics_snapshot' => $safe,
            ]);

            try {
                AlertLog::create([
                    'monitored_app_id' => $app->id,
                    'channel' => 'internal',
                    'type' => 'anomaly_response_time',
                    'message' => 'Detected repeated response_time == 120 in incoming metrics',
                    'status' => 'detected',
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to create AlertLog for response_time anomaly', ['error' => $e->getMessage()]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Metric recorded successfully',
            'data' => $metric
        ]);
    }
}
