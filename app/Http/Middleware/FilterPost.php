<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FilterPost
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
        $post= $request->route('post');
        $thread = \App\Models\Thread::FindOrFail($post->thread_id);
        $channel= \App\Models\Channel::FindOrFail($thread->channel_id);
        if ($thread->public){
            if ($channel->channel_state>=10){//作业，后花园，以及管理界面
                if (Auth::check()){
                    if (Auth::user()->group > $channel->channel_state){
                        return $next($request);
                    }else{
                        return redirect()->route('error', ['error_code' => '403']);
                    }
                }
                return redirect('login')->with("warning", "请登陆后再访问该版面");
            }else{
                return $next($request);
            }
        }else{//并非公开贴
            if ((Auth::check())&&((Auth::user()->admin)||($thread->user_id == Auth::id()))){//本人或管理员可见
                return $next($request);
            }
            return redirect()->route('error', ['error_code' => '403']);
        }
    }
}
