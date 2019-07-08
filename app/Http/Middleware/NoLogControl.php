<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use CacheUser;

class NoLogControl
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
            if(CacheUser::findCachedUser(Auth::id())->no_logging){
                Auth::logout();
                return $next($request);
            }
        }
        return $next($request);
    }
}
