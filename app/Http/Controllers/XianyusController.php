<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Thread;
use App\Xianyu;
use Auth;
use App\Models\User;

class XianyusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   public function __construct()
   {
     $this->middleware('auth');
   }

    public function index()
    {
        //
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vote(Thread $thread, Request $request)
    {
        //检查最近一周内，该ip或者用户名是否投过咸鱼
        $xianyus = $thread->recentXianyus();
        $ip = $request->getClientIp();
        $user = Auth::user();
        $id = $user->id;
        if ($thread->xianyu_voted($user, $ip)) {
           return back()->with("info", "本周内您的账户/IP，已为此条主题投过咸鱼");
        }
        //检查咸鱼是否足够
        if ($user->xianyu <= 0){
           return back()->with("info", "抱歉，您的咸鱼不足");
        }
        //没投过的情况
        $xianyu = Xianyu::create([
          'user_ip' => $ip,
          'user_id' => $id,
          'thread_id' => $thread->id,
        ]);
        $thread->increment('xianyu');
        $thread->update(['lastresponded_at' => Carbon::now()]);
        $user->update(['lastresponded_at' => Carbon::now()]);
        $user->decrement('xianyu');
        $user->increment('jifen', 5);
        $thread->creator->increment('xianyu', 5);//每当主题被人扔咸鱼，自己得5咸鱼
        $thread->creator->increment('jifen', 5);//每当主题被人扔咸鱼，自己得5积分
        return back()->with("success", "您已成功投掷咸鱼~");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Xianyu  $xianyu
     * @return \Illuminate\Http\Response
     */
    public function show(Xianyu $xianyu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Xianyu  $xianyu
     * @return \Illuminate\Http\Response
     */
    public function edit(Xianyu $xianyu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Xianyu  $xianyu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Xianyu $xianyu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Xianyu  $xianyu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Xianyu $xianyu)
    {
        //
    }
}
