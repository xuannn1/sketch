<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\ConstantObjects;
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
            $channel= ConstantObjects::allChannels()->keyBy('id')->get($thread->channel_id);
            if((($thread->is_public)&&($channel->is_public))||auth('api')->check()&&(auth('api')->user()->canSeeChannel($channel)||auth('api')->id()===$thread->user_id)){
                return $next($request);
            }
            return response()->error(config('error.403'),403);
        }
        return response()->error(config('error.404'),404);
    }
}
