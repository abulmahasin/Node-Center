<?php

namespace Tests\Feature;

use App\Console\Commands\SendDashboardMetrics;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\TestCase;

class SendDashboardMetricsTest extends TestCase
{
    public function test_it_sends_response_summary_metrics_with_the_payload(): void
    {
        require_once base_path('agent-script/SendDashboardMetrics.php');

        Cache::flush();
        Cache::put('response_metrics', [
            'history' => [
                ['duration_ms' => 120.5, 'status' => 200],
                ['duration_ms' => 80.2, 'status' => 200],
            ],
            'summary' => [
                'count' => 2,
                'average_ms' => 100.35,
                'max_ms' => 120.5,
                'min_ms' => 80.2,
                'last_status' => 200,
            ],
        ], 3600);

        Http::fake();
        putenv('DASHBOARD_API_TOKEN=test-token');
        putenv('DASHBOARD_MONITOR_URL=https://dashboard.test');

        $command = new SendDashboardMetrics();
        $command->setLaravel($this->app);
        $output = new BufferedOutput();
        $style = new OutputStyle(new ArrayInput([]), $output);
        $command->setOutput($style);
        $command->handle();

        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            $payload = $request->data();

            return $request->url() === 'https://dashboard.test/api/metrics'
                && $payload['response_time'] == 100.35
                && $payload['response_metrics_summary']['average_ms'] == 100.35
                && $payload['response_metrics_summary']['last_status'] == 200;
        });
    }
}
