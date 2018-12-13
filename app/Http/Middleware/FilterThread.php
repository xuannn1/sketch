<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Closure;
use Auth;
use App\Helpers\Helper;

class FilterThread
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
        $thread = $request->route('thread');
        $channel= Helper::allChannels()->keyBy('id')->get($thread->channel_id);
        if ((Auth::check())&&((Auth::user()->admin)||($thread->user_id == Auth::id()))){//原作者本人或管理员可见帖子
            return $next($request);
        }elseif($thread->public){
            if ($channel->channel_state>=10){//作业，后花园，以及管理界面
                if (Auth::check()){
                    if (Auth::user()->group > $channel->channel_state){
                        return $next($request);
                    }else{
                        return redirect()->route('error', ['error_code' => '403']);
                    }
                }
                return redirect('login')->with("warning", "请登陆后再访问该版面");
            }else{//这里新去掉了关于边缘的限制（20180721）
                return $next($request);
            }
        }elseif(Auth::check()){//假如这个人是合作者
            $collaboration = DB::table('collaborations')->where([['thread_id', '=', $thread->id],['user_id','=',Auth::id()]])->first();
            if($collaboration){
                return $next($request);
            }else{
                return redirect()->route('error', ['error_code' => '403']);
            }
        }
        return redirect()->route('error', ['error_code' => '403']);
    }
}
