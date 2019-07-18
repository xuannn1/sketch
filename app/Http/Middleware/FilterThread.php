<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
use App\Sosadfun\Traits\FindThreadTrait;

class FilterThread
{
    use FindThreadTrait;
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {

        $thread = $this->findThread($request->route('thread'));
        if(!$thread){ // 假如有东西找不到，那必然不能登陆
            abort(403);
        }
        $channel= $thread->channel();
        if(!$channel){ // 假如有东西找不到，那必然不能登陆
            abort(403);
        }

        if($channel->is_public&&$thread->is_public&&!$thread->is_bianyuan){// 公共非边
            return $next($request);
        }

        if($channel->is_public&&$thread->is_public&&$thread->is_bianyuan){// 公共边
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
