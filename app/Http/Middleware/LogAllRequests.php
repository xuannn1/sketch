<?php

namespace App\Http\Middleware;

use Closure;
use Log;
class LogAllRequests
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */

    public function handle($request, \Closure  $next)
	{
        $response = $next($request);

        Log::channel('records')->info('app.requests', ['request' => (string)$request]);

        return $response;
	}
}
