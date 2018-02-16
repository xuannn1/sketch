<?php

namespace App\Http\Middleware;

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
      $channel_state = \App\Channel::FindOrFail($request->route('channel')->id)->channel_state;
    if ($channel_state>=10){
      if (Auth::check()){
          if ($request->user()->group > $channel_state){
             return $next($request);
          }
          return redirect()->route('error', ['error_code' => '403']);
      }
      return redirect('login');
    }
     return $next($request);
    }
}
