<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class MeasureResponseTime
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $duration = (microtime(true) - $start) * 1000;

        $history = Cache::get('response_times', []);
        $history[] = $duration;
        if (count($history) > 100) array_shift($history);
        Cache::put('response_times', $history, 3600);

        return $response;
    }
}