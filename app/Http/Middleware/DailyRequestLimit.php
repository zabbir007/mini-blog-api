<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class DailyRequestLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $identifier = $request->user() ? 'user:' . $request->user()->id : 'ip:' . $request->ip();
        $cacheKey = $identifier . ':' . now()->format('Y-m-d');

        $requestCount = Cache::get($cacheKey, 0);

        if ($requestCount >= $this->dailyLimit) {
            return response()->json([
                'error' => 'API rate limit exceeded. Try again later.'
            ], 429);
        }

        Cache::put($cacheKey, $requestCount + 1, now()->endOfDay());

        return $next($request);
    }
}
