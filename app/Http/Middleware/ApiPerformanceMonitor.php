<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiPerformanceMonitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = microtime(true);
        $queryCount = 0;
        $queryTimeMs = 0.0;

        DB::listen(function ($query) use (&$queryCount, &$queryTimeMs): void {
            $queryCount++;
            $queryTimeMs += (float) $query->time;
        });

        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        $durationMs = (microtime(true) - $startedAt) * 1000;
        $targetMs = (int) config('performance.api_target_ms', 200);

        $response->headers->set('X-Response-Time-Ms', (string) round($durationMs, 2));
        $response->headers->set('X-Db-Query-Count', (string) $queryCount);
        $response->headers->set('X-Db-Query-Time-Ms', (string) round($queryTimeMs, 2));

        if ($durationMs > $targetMs) {
            Log::warning('API response slower than target', [
                'method' => $request->method(),
                'path' => $request->path(),
                'status' => $response->getStatusCode(),
                'duration_ms' => round($durationMs, 2),
                'target_ms' => $targetMs,
                'db_query_count' => $queryCount,
                'db_query_time_ms' => round($queryTimeMs, 2),
            ]);
        }

        return $response;
    }
}
