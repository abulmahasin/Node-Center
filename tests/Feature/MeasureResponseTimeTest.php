<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class MeasureResponseTimeTest extends TestCase
{
    public function test_response_metrics_are_cached_with_summary(): void
    {
        Cache::flush();

        Route::get('/test-response-time', fn () => response()->json(['ok' => true]));

        $response = $this->get('/test-response-time');

        $response->assertOk();

        $metrics = Cache::get('response_metrics');

        $this->assertNotNull($metrics);
        $this->assertArrayHasKey('history', $metrics);
        $this->assertArrayHasKey('summary', $metrics);
        $this->assertNotEmpty($metrics['history']);
        $this->assertSame('GET', $metrics['history'][0]['method']);
        $this->assertSame('/test-response-time', $metrics['history'][0]['uri']);
        $this->assertIsNumeric($metrics['summary']['average_ms']);
        $this->assertSame(1, $metrics['summary']['count']);
    }
}
