<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Thread;
use App\Post;
use Carbon\Carbon;
use Auth;
use App\Chapter;
use App\Activity;
use App\Collection;

class PostsController extends Controller
{

   public function __construct()
   {
      $this->middleware('auth');
   }
   public function create_post_form(Request $request,Thread $thread)
   {
      return view('threads.reply',compact('request','thread'));
   }
    public function store(Request $request, Thread $thread)
    {
      $user = Auth::user();
      if(request('store_button')){
         if ((!$thread->locked)&&(($thread->public)||($thread->user_id==Auth::id()))){
            $this->validate($request, [
              'body' => 'required|string|min:10',
              'reply_to_post' => 'required|numeric',
              'majia' => 'string|max:10',
            ]);
           if(request('anonymous')){
              $anonymous = true;
              $majia = request('majia');
              $user->update(['majia'=>$majia]);
           }else{
              $anonymous = false;
              $majia = null;
           }
           if (request('default_chapter_id')!="0"){
              $chapter = Chapter::find(request('default_chapter_id'));
              if ((!$chapter)||($thread->book_id == 0)||($chapter->book_id != $thread->book->id)){
                 return redirect()->route('error', ['error_code' => '403']);
              }
           }
           $markdown = request('markdown')? true: false;
           $chapter_id = request('default_chapter_id');
           $reply_to_post_id = request('reply_to_post');
           if ($reply_to_post_id!= "0"){
              $reply = Post::find(request('reply_to_post'));
              if ((!$reply)||($reply->thread_id != $thread->id)){
                 return redirect()->route('error', ['error_code' => '403']);
              }
              if($reply->maintext){
                 $reply_to_post_id = 0;
              }
              $chapter_id = $reply->chapter_id;
           }
           $post = Post::create([
              'body' => request('body'),
              'user_id' => Auth::id(),
              'thread_id' => $thread->id,
              'reply_to_post_id' => $reply_to_post_id,
              'chapter_id' =>  $chapter_id,
              'anonymous' => $anonymous,
              'majia' => $majia,
              'markdown' => $markdown,
           ]);
           $post->checklongcomment();
           if ($chapter_id !=0){//如果是给某个章节回帖，这个章节的数据更新
              $post->chapter->increment('responded');
           }
           if (Auth::id()!=$thread->user_id){//不是给自己的主题顶帖，那么送出跟帖提醒
              $post_activity = Activity::create([
                 'type' => 1,
                 'item_id' => $post->id,
                 'user_id' => $thread->user_id,
              ]);
              $thread->creator->increment('post_reminders');
           }

           if ($thread->book_id==0){//如果这不是一篇文章，那么告诉所有收藏本讨论、愿意接受更新的读者, 这里发生了更新
             DB::table('collections')
             ->join('users','users.id','=','collections.user_id')
             ->where([['collections.thread_id','=',$thread->id],['collections.keep_updated','=',true],['collections.user_id','<>',$user->id]])
             ->update(['collections.updated'=>1,'users.collection_threads_updated'=>DB::raw('users.collection_threads_updated + 1')]);
            }
           if(($reply_to_post_id!=0)&&(Auth::id()!=$reply->user_id)&&($reply->user_id!=$thread->user_id)){//回帖了，不是给自己的帖子回帖，回帖对象也不是楼主，那么送出回帖提醒
              $reply_activity = Activity::create([
                 'type' => 2,
                 'item_id' => $post->id,
                 'user_id' => $reply->user_id,
              ]);
              $reply->owner->increment('reply_reminders');
           }
           $thread->update([
              'lastresponded_at' => Carbon::now(),
              'last_post_id' => $post->id,
           ]);
           $thread->increment('responded');
           $user->jifen+=2;
           $user->xianyu+=1;
           $user->save();
           return redirect(route('thread.showpost',$post->id))->with("success", "您已成功回帖");
        }else{
           return redirect()->back()->with("danger", "抱歉，本主题锁定或设为隐私，不能回帖");
        }
     }

    }
     public function edit(Post $post)
     {
        $thread=$post->thread;
        if ($thread->locked){
           return redirect()->route('error', ['error_code' => '403']);
        }else{
           return view('posts.post_edit', compact('post'));
        }
     }

     public function update(Request $request, Post $post)
     {
        $thread=$post->thread;
        if ((Auth::id() == $post->user_id)&&(!$thread->locked))
        {
            $this->validate($request, [
             'body' => 'required|string|min:10',
             'majia' => 'string|max:10',
            ]);
            $anonymous = request('anonymous')? true: false;
            if (($post->long_comment)&&(request('title'))){
               $this->validate($request, [
                  'title' => 'required|string|max:35',
               ]);
               $post->update([
                  'title' => request('title'),
               ]);
            }
            $markdown = request('markdown')? true: false;
            $post->update([
               'body' => request('body'),
               'edited_at' => Carbon::now(),
               'anonymous' => $anonymous,
               'markdown' => $markdown,
            ]);
            $string = preg_replace('/[[:punct:]\s\n\t\r]/','',$post->body);
            $characters = iconv_strlen($string, 'utf-8');
            if ($characters>Config::get('constants.longcomment_lenth')){
               $post->update(['long_comment' => true,]);
            }
            return redirect()->route('thread.showpost', $post->id)->with("success", "您已成功修改帖子");
         }else{
            return redirect()->route('error', ['error_code' => '403']);
        }
     }
     public function show(Post $post)
     {
        $thread = $post->thread->load('label','channel');
        $post->load('owner','reply_to_post.owner');
        $postcomments = $post->allcomments()->with('owner')->paginate(Config::get('constants.items_per_page'));
        $defaultchapter=$thread->chapter_id;
        return view('posts.show',compact('post','thread','postcomments','defaultchapter'));
     }

     public function destroy($id){
        $post = Post::findOrFail($id);
        $thread=$post->thread;
        if((!$thread->locked)&&(Auth::id()==$post->user_id)){
           if(($post->maintext)&&($post->chapter_id !=0)){
             $chapter = $post->chapter;
             if($chapter->post_id == $post->id){
                $chapter->delete();
             }
          }
           $post->delete();
           return redirect()->route('home')->with("success","已经删帖");
        }else{
           return redirect()->route('error', ['error_code' => '403']);
        }
     }
}
