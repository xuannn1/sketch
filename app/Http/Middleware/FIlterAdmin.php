<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\User;

class FilterAdmin
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
      if(!Auth::user()->admin){
         return redirect()->route('error', ['error_code' => '403']);
      }else{
         return $next($request);
      }
    }
}
