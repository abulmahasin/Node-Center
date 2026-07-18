<?php

namespace Tests\Feature;

use App\Services\HealthScoringService;
use Tests\TestCase;

class HealthScoringServiceTest extends TestCase
{
    public function test_health_score_uses_configurable_thresholds(): void
    {
        $service = new HealthScoringService();
        $result = $service->evaluate([
            'is_fresh' => true,
            'response_time' => 200,
            'cpu_usage' => 50,
            'memory_usage' => 60,
            'error_count' => 0,
            'failed_jobs' => 0,
            'db_latency' => 100,
            'cache_latency' => 20,
        ]);

        $this->assertSame(100, $result['score']);
        $this->assertSame('Healthy', $result['label']);
        $this->assertSame('#16a34a', $result['color']);
    }
}
