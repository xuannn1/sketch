<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
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
            //统计注册用户在线数量
            if(!Cache::has('usr-on-' . Auth::id())){//假如距离上次cache的时间已经超过了默认时间
                $record = LoggingStatus::updateOrCreate([
                    'user_id' => Auth::id(),
                ],[
                    'logged_on' => time(),
                    'ip' => request()->ip(),
                ]);
                $expiresAt = Carbon::now()->addMinutes(config('constants.online_count_interval'));
                Cache::put('usr-on-' . Auth::id(), true, $expiresAt);
                Auth::user()->reward('online_reward');
            }
        }
        return $next($request);
    }
}
