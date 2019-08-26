<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use Cache;
use Carbon;
use CacheUser;

class LogUserActivity
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
            if (Cache::has('usr-clicks-'.Auth::id())){
                Cache::increment('usr-clicks-'.Auth::id());
            }else{
                Cache::put('usr-clicks-'.Auth::id(),1,10080);
            }

            if(!Cache::has('usr-on-'.Auth::id())){//假如距离上次cache的时间已经超过了默认时间
                $info = CacheUser::Ainfo();
                $value = (int)Cache::get('usr-clicks-'.Auth::id());
				if($value>1){
					$info->increment('daily_clicks', $value);
                    Cache::put('usr-clicks-'.Auth::id(),0, 10080);
				}
                Cache::put('usr-on-'.Auth::id(),1,30);
            }

            if(!Cache::has('usr-ip-on-'.Auth::id())){//1天(?)记录一次IP活动
                $user_activity = \App\Models\TodayUsersActivity::firstOrCreate([
                    'user_id' =>Auth::id(),
                    'ip' => request()->ip(),
                ]);
                Cache::put('usr-ip-on-'.Auth::id(),1,1440);
            }
        }
        return $next($request);
    }
}
