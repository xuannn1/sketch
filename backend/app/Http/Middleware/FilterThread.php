<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helper;
use Auth;
use App\Models\Thread;

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
        $thread = Thread::find($request->route('thread'));
        if($thread){
            $channel= Helper::allChannels()->keyBy('id')->get($thread->channel_id);
            $thread_group = max(($thread->is_bianyuan ? 3:0), $channel->channel_state);
            $user_group = Auth::guard('api')->check()? Auth::guard('api')->user()->group :2;
            if ((($thread->is_public)&&($user_group>$thread_group))||(Auth::guard('api')->check()&&((Auth::guard('api')->user()->is_admin)||(Auth::guard('api')->id()===$thread->user_id)))){
                return $next($request);
            }
            return response()->error(config('error.403'),403);
        }
        return response()->error(config('error.404'),404);
    }
}
