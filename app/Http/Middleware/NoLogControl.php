<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use CU;
use Illuminate\Support\Facades\Cache;
use Carbon;
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
            if(Auth::user()->no_logging){
                $info = CacheUser::info(Auth::id());
                $msg = $info->no_logging_until&&$info->no_logging_until>Carbon::now() ? $info->no_logging_until->setTimeZone('Asia/Shanghai'):'';
                Auth::logout();
                abort(495,$msg);
            }
        }
        return $next($request);
    }
}
