<?php

namespace App\Http\Middleware;

use Closure;

class FilterEditor
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
        //必须是编辑/资深编辑/管理员，才能负责这些事情
        if(auth('api')->check()&&(auth('api')->user()->inRole('editor')||auth('api')->user()->inRole('senior_editor')||auth('api')->user()->inRole('admin')){
            return $next($request);
        }
        return response()->error(config('error.401'),401);
    }
}
