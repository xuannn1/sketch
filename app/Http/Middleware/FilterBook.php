<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FilterBook
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
        $book = $request->route('book');
        $thread = $book->thread;
        if ($thread->public){//公开帖
            return $next($request);//这里新去掉了边缘限制文必须登陆才能看的限制
        }else{//并非公开贴
            if ((Auth::check())&&((Auth::user()->admin)||($thread->user_id == Auth::id()))){//本人可见
                return $next($request);
            }
            return redirect()->route('error', ['error_code' => '403']);
        }
    }
}
