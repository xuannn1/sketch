<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Closure;
use Auth;
use App\Helpers\Helper;
use App\Helpers\ThreadObjects;

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

        $thread = ThreadObjects::thread($request->route('thread'));
        if(!$thread){ // 假如有东西找不到，那必然不能登陆
            return redirect()->route('error', ['error_code' => '404']);
        }
        $channel= $thread->channel();
        if(!$channel){ // 假如有东西找不到，那必然不能登陆
            return redirect()->route('error', ['error_code' => '404']);
        }

        if($channel->is_public&&$thread->public&&!$thread->bianyuan){// 公共非边
            return $next($request);
        }

        if($channel->is_public&&$thread->public&&$thread->bianyuan){// 公共边
            if(Auth::check()&&!Auth::user()->activated){
                return redirect()->route('users.edit', Auth::id())->with("warning", "您的邮箱尚未激活，请激活后再访问该版面");
            }
            return $next($request);
        }

        if(!Auth::check()){ //并非公共的，都需要登陆
            return redirect('login')->with("warning", "请登陆后再访问该版面");
        }

        if($thread->user_id === Auth::id()||Auth::user()->isAdmin()){ //本人或者管理，可以任意访问
            return $next($request);
        }

        if($channel->type==='homework'&&Auth::user()->seeHomework()){ //作业区，做作业的人，可以访问
            return $next($request);
        }
        
        return redirect('home');
    }
}
