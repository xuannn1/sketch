<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Activity;
use Auth;

class PostCommentsController extends Controller
{
   public function __construct()
  {
     $this->middleware('auth');
  }

   public function store(Request $request, Post $post)
   {
      $user = Auth::user();
      $thread=$post->thread;
      if(!$thread->locked){
         $this->validate($request, [
            'body' => 'required|string|max:100',
         ]);
         if(request('anonymous')){
            $anonymous = true;
            $majia = request('majia');
            $user->update(['majia'=>$majia]);
         }else{
            $anonymous = false;
            $majia = null;
         }
         $postcomment = PostComment::create([
            'body' => request('body'),
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'anonymous' => $anonymous,
            'majia' => $majia,
         ]);

         if (Auth::id()!=$post->user_id){//点评的帖子，对方不是自己
            $postcomment_acticity = Activity::create([
               'type' => 3,
               'item_id' => $postcomment->id,
               'user_id' => $post->user_id,
            ]);
            $post->owner->increment('postcomment_reminders');
         }
         $user->reward("regular_post_comment");
         return back()->with("success", "您已成功点评");
      }else{
         return redirect()->back()->with("danger","抱歉，本帖锁定，暂时不能点评");
      }

   }
   public function destroy($id)
   {
      $postcomment=PostComment::find($id);
      if(Auth::id()==$postcomment->user_id){
         $postcomment->delete();
      }else{
         return redirect()->route('error', ['error_code' => '403']);
      }

   }
}
