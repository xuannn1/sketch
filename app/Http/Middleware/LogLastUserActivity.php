<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\LoggingStatus;

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
            $record = LoggingStatus::updateOrCreate([
                'user_id' => Auth::id(),
            ],[
                'logged_on' => time(),
            ]);
            //Cache::put('-usr-on-' . Auth::user()->id, true, $expiresAt);
            Auth::user()->increment('daily_clicks');
        }
        return $next($request);
    }
}
