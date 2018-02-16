<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Shengfan;
use Auth;
use Carbon\Carbon;

class ShengfansController extends Controller
{
   public function __construct()
  {
    $this->middleware('auth');
  }



   public function vote(Post $post, Request $request)
   {  //是否投喂过
      $user = Auth::user();
      if ($post->shengfan_voted($user)){
         return back()->with("info", "抱歉，您已为该回帖投过剩饭");
      }
      //没投过的情况，检查数目是否合适
      $this->validate($request, [
          'shengfan_num' => 'required|min:-1|max:10',
        ]);
      $shengfan_num = request('shengfan_num');
      //合适的时候
      if ($user->shengfan < $shengfan_num){
         return back()->with("info", "抱歉，您的剩饭不足");
      }
      $id = $user->id;

      $shengfan = Shengfan::create([
         'user_id' => $id,
         'shengfan_num' => $shengfan_num,
         'post_id' => $post->id,
       ]);
       $thread = $post->thread;
       $thread->increment('shengfan', $shengfan_num);//每当主题被人扔剩饭，主题得同等剩饭
       $user->update(['lastresponded_at' => Carbon::now()]);
       $user->decrement('shengfan', $shengfan_num);//动作发起者自己减少对应剩饭
       $user->increment('jifen', $shengfan_num);
       $author = $thread->creator;
       $author->increment('shengfan', $shengfan_num);//每当主题被人扔剩饭，自己得等量剩饭
       $author->increment('jifen', 1);//每当主题被人扔剩饭，自己得等量积分
       return back()->with("success", "您已成功投掷剩饭~");
   }

}
