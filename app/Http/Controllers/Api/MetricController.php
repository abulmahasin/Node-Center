<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        $metric = $app->metrics()->create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Metric recorded successfully',
            'data' => $metric
        ]);
    }
}
