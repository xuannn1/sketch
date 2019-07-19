<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Closure;
use Auth;

class FilterChannel
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
        $channel = collect(config('channel'))->keyby('id')->get($request->route('channel'));
        if(!$channel){
            abort(404,'未找到频道');
        }
        if(!$channel->is_public&&!Auth::check()){
            return redirect('login')->with("warning", "请登陆后再访问该版面");
        }
        if(!$channel->is_public&&Auth::check()&&!Auth::user()->canSeeChannel($channel->id)){
            abort(403,'您的权限不足，无法访问该界面');
        }
        return $next($request);
    }
}
