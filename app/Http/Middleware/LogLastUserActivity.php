<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Session;
use App\Models\LoggingStatus;
use App\Models\SessionStatus;
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
            //统计注册用户在线数量（仅统计在默认登陆时间间隔内在线的人，避免过度操作数据库）
            if (Cache::has('-usr-clicks-'.Auth::id())){
                Cache::increment('-usr-clicks-'.Auth::id());
            }else{
                Cache::put('-usr-clicks-'.Auth::id(),1,Carbon::now()->addDay(1));
            }
            if(!Cache::has('-usr-on-' . Auth::id())){//假如距离上次cache的时间已经超过了默认时间
                $record = LoggingStatus::updateOrCreate([
                    'user_id' => Auth::id(),
                ],[
                    'logged_on' => time(),
                ]);
                $expiresAt = Carbon::now()->addMinutes(config('constants.online_count_interval'));
                Cache::put('-usr-on-' . Auth::id(), true, $expiresAt);
                Auth::user()->increment('daily_clicks', Cache::pull('-usr-clicks-'.Auth::id()));
                Auth::user()->reward('online_reward');
            }
        }else{
            //统计游客在线数量（仅统计在默认登陆时间间隔内在线的人，避免过度操作数据库）
            if(!Cache::has('-session-on-' . Session::get('_token'))){//假如距离上次cache的时间已经超过了默认时间
                $record = SessionStatus::updateOrCreate([
                    'session_token' => Session::get('_token'),
                    'logged_on' => time(),
                    'session_ip' => request()->ip(),
                ]);
                $expiresAt = Carbon::now()->addMinutes(config('constants.online_count_interval'));
                Cache::put('-session-on-' . Session::get('_token'), true, $expiresAt);
            }
        }
        return $next($request);
    }
}
