<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DailyLimitMiddleware
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
        $key = $request->user() ? 'user:' . $request->user()->id : 'ip:' . $request->ip();
        $limit = 1000;
        $count = Cache::get($key, 0);

        if ($count >= $limit) {
            return response()->json(['error' => 'API rate limit exceeded. Try again later.'], 429);
        }

        Cache::put($key, $count + 1, now()->endOfDay());
        return $next($request);
    }
}
