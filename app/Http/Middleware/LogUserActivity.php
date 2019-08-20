<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use Cache;
use Carbon;
use App\Models\OnlineStatus;
use App\Models\HistoricalUsersActivity;
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
                Cache::put('usr-clicks-'.Auth::id(),1,Carbon::now()->addDay(1));
            }
            if(!Cache::has('usr-on-' . Auth::id())){//假如距离上次cache的时间已经超过了默认时间
                $online_status = OnlineStatus::on('mysql::write')->updateOrCreate([
                    'user_id' => Auth::id(),
                ],[
                    'online_at' => Carbon::now(),
                ]);
                $expiresAt = Carbon::now()->addMinutes(config('constants.online_interval'));
                Cache::put('usr-on-' . Auth::id(), true, $expiresAt);
                Auth::user()->info->increment('daily_clicks', (int)Cache::pull('usr-clicks-'.Auth::id()));
            }
            if(!Cache::has('usr-ip-on-' . Auth::id())){//一天记录一次活动
                $user_activity = HistoricalUsersActivity::create([
                    'user_id' =>Auth::id(),
                    'ip' => request()->ip(),
                ]);
                Cache::put('usr-ip-on-' . Auth::id(), true, Carbon::now()->addDay(1));
            }
        }
        return $next($request);
    }
}
