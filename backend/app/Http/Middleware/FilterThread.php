<?php

namespace App\Http\Middleware;

use Closure;

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
        $channel = $thread->channel();
        if($thread){
            if((($thread->is_public)&&($channel->is_public))||auth('api')->check()&&(auth('api')->user()->canSeeChannel($thread->channel_id)||auth('api')->id()===$thread->user_id)){
                return $next($request);
            }
            return response()->error(config('error.403'),403);
        }
        return response()->error(config('error.404'),404);
    }
}
