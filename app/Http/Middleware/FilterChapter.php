<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FilterChapter
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
        $chapter = $request->route('chapter');
        $thread = $chapter->book->thread;
        if ($thread->public){
            return $next($request);//新去掉了边缘限制20180720
        }else{//并非公开贴
            if ((Auth::check())&&((Auth::user()->admin)||($thread->user_id == Auth::id()))){//本人或管理员可见
                return $next($request);
            }
            return redirect()->route('error', ['error_code' => '403']);
        }
    }
}
