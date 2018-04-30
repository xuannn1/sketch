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
            if($thread->bianyuan){
                if (Auth::check()) {
                    return $next($request);
                }else{
                    return redirect('login')->with("warning", "边缘文章请登陆后查看");
                }
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
