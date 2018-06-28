<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class LogLastUserActivity
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            $expiresAt = Carbon::now()->addMinutes(30);
            Cache::put('-usr-on-' . Auth::user()->id, true, $expiresAt);
            Auth::user()->increment('daily_clicks');
        }
        return $next($request);
    }
}
