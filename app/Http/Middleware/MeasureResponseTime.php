<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class MeasureResponseTime
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        $hasError = false;
        $statusCode = 500;

        try {
            $response = $next($request);
            $statusCode = $response->status();
            $hasError = $response->status() >= 500;
            return $this->recordMetric($request, $response, $start, $statusCode, $hasError);
        } catch (\Throwable $e) {
            $hasError = true;
            $statusCode = 500;
            throw $e;
        } finally {
            if (isset($response)) {
                // handled in return path
            }
        }
    }

    protected function recordMetric(Request $request, $response, float $start, int $statusCode, bool $hasError): Response
    {
        $duration = (microtime(true) - $start) * 1000;

        $metric = [
            'method' => $request->method(),
            'uri' => $request->getRequestUri(),
            'status' => $statusCode,
            'duration_ms' => round($duration, 2),
            'has_error' => $hasError,
            'timestamp' => now()->toISOString(),
        ];

        $metrics = Cache::get('response_metrics', ['history' => [], 'summary' => []]);
        $history = is_array($metrics['history'] ?? null) ? $metrics['history'] : [];
        $history[] = $metric;

        if (count($history) > 100) {
            array_shift($history);
        }

        $summary = $this->buildSummary($history);
        $metrics = [
            'history' => $history,
            'summary' => $summary,
            'last_seen_at' => now()->toISOString(),
        ];

        Cache::put('response_metrics', $metrics, 60);

        return $response;
    }

    protected function buildSummary(array $history): array
    {
        if (empty($history)) {
            return [
                'count' => 0,
                'average_ms' => 0,
                'max_ms' => 0,
                'min_ms' => 0,
                'last_status' => null,
            ];
        }

        $durations = array_column($history, 'duration_ms');
        $latest = end($history);

        $errorCount = count(array_filter($history, fn ($item) => !empty($item['has_error'])));
        $successCount = count($history) - $errorCount;
        $recent = array_slice($history, -10);
        $recentDurations = array_column($recent, 'duration_ms');

        return [
            'count' => count($history),
            'average_ms' => round(array_sum($durations) / count($durations), 2),
            'max_ms' => round(max($durations), 2),
            'min_ms' => round(min($durations), 2),
            'last_status' => $latest['status'] ?? null,
            'error_count' => $errorCount,
            'success_count' => $successCount,
            'recent_average_ms' => !empty($recentDurations) ? round(array_sum($recentDurations) / count($recentDurations), 2) : 0,
            'error_rate' => count($history) > 0 ? round(($errorCount / count($history)) * 100, 2) : 0,
        ];
    }
}