<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FilterLabel
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
      $label =$request->route('label');
      $channel_state = $label->channel->channel_state;
      if ($channel_state>=10){
         if (Auth::check()){
             if ($request->user()->group > $channel_state){
                return $next($request);
             }return redirect()->route('error', ['error_code' => '403']);
         }return redirect('login');
      }return $next($request);
    }
}
