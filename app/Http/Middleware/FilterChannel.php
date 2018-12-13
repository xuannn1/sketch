<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Channel;
use App\Helpers\Helper;

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
        $channel = Helper::allChannels()->keyBy('id')->get($request->route('channel'));
        if ($channel->channel_state>=10){
            if (Auth::check()){
                if ($request->user()->group > $channel->channel_state){
                    return $next($request);
                }
                return redirect()->route('error', ['error_code' => '403']);
            }
            return redirect('login');
        }
        return $next($request);
    }
}
