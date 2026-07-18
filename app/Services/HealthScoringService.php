<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Database\QueryException;

class HealthScoringService
{
    public function evaluate(array $metrics): array
    {
        $freshWindow = $this->getSetting('health_fresh_window_minutes', 5);
        $responseWarning = $this->getSetting('health_response_time_warning_ms', 400);
        $responseCritical = $this->getSetting('health_response_time_critical_ms', 800);
        $cpuWarning = $this->getSetting('health_cpu_warning_percent', 70);
        $cpuCritical = $this->getSetting('health_cpu_critical_percent', 90);
        $memoryWarning = $this->getSetting('health_memory_warning_percent', 80);
        $memoryCritical = $this->getSetting('health_memory_critical_percent', 95);
        $dbWarning = $this->getSetting('health_db_latency_warning_ms', 200);
        $cacheWarning = $this->getSetting('health_cache_latency_warning_ms', 50);

        $isFresh = (bool) ($metrics['is_fresh'] ?? false);
        $responseTime = (float) ($metrics['response_time'] ?? 0);
        $cpu = (float) ($metrics['cpu_usage'] ?? 0);
        $memory = (float) ($metrics['memory_usage'] ?? 0);
        $errorCount = (int) ($metrics['error_count'] ?? 0);
        $failedJobs = (int) ($metrics['failed_jobs'] ?? 0);
        $dbLatency = (float) ($metrics['db_latency'] ?? 0);
        $cacheLatency = (float) ($metrics['cache_latency'] ?? 0);

        $score = 0;
        $score += $isFresh ? 25 : 0;

        if ($responseTime <= $responseWarning) {
            $score += 25;
        } elseif ($responseTime <= $responseCritical) {
            $score += 15;
        }

        if ($cpu <= $cpuWarning) {
            $score += 15;
        } elseif ($cpu <= $cpuCritical) {
            $score += 8;
        }

        if ($memory <= $memoryWarning) {
            $score += 10;
        } elseif ($memory <= $memoryCritical) {
            $score += 5;
        }

        $score += $errorCount === 0 ? 10 : 0;
        $score += $failedJobs === 0 ? 10 : 0;
        $score += $dbLatency <= $dbWarning ? 5 : 0;
        $score += $cacheLatency <= $cacheWarning ? 5 : 0;

        $score = min(100, max(0, $score));

        if ($score >= 85) {
            $label = 'Healthy';
            $color = '#16a34a';
        } elseif ($score >= 60) {
            $label = 'Warning';
            $color = '#d97706';
        } else {
            $label = 'Critical';
            $color = '#dc2626';
        }

        return [
            'score' => $score,
            'label' => $label,
            'color' => $color,
            'fresh_window_minutes' => $freshWindow,
        ];
    }

    protected function getSetting(string $key, float|int $default): float|int
    {
        try {
            $value = Setting::get($key, $default);
            return is_numeric($value) ? (float) $value : (float) $default;
        } catch (QueryException | \Throwable $e) {
            return (float) $default;
        }
    }
}
