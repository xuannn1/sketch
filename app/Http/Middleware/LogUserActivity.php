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
                Cache::put('usr-clicks-'.Auth::id(),1, 1440);
            }
            if(!Cache::has('usr-on-'.Auth::id())){//假如距离上次cache的时间已经超过了默认时间
                $online_status = \App\Models\OnlineStatus::on('mysql::write')->updateOrCreate([
                    'user_id' => Auth::id(),
                ],[
                    'online_at' => Carbon::now(),
                ]);
                Cache::put('usr-on-'.Auth::id(), 1, 30);
                $info = CacheUser::Ainfo();
                $value = (int)Cache::pull('usr-clicks-'.Auth::id());
				if($value>1){ //为了效率考虑，控制这个值的更新，计算出来的点击数会偏小
					$info->increment('daily_clicks', $value);
				}
            }
            if(!Cache::has('usr-ip-on-'.Auth::id())){//一天记录一次IP活动
                $user_activity = \App\Models\TodayUsersActivity::firstOrCreate([
                    'user_id' =>Auth::id(),
                    'ip' => request()->ip(),
                ]);
                Cache::put('usr-ip-on-'.Auth::id(), 1, 1440);
            }
        }
        return $next($request);
    }
}
