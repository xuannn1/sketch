<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Quote;
use App\Models\Channel;
use App\Models\Label;
use App\Models\Thread;
use App\Models\Post;
use App\Models\User;
use App\Models\PostComment;
use App\Administration;
use App\Models\Book;
use App\Message;
use Auth;
use Carbon\Carbon;

class AdminsController extends Controller
{
   public function __construct()
   {
     $this->middleware('filter_admin');
   }
   public function index()
   {
      return view('admin.index');
   }
   public function quotesreview()
   {
      $quotes = Quote::orderBy('created_at', 'desc')->paginate(config('constants.index_per_page'));
      return view('admin.quotesreview', compact('quotes'));
   }

   public function quoteapprove(Quote $quote)
   {
      $quote->approved = true;
      $quote->reviewed = true;
      $quote->update();
      return back();
   }
   public function quotedisapprove(Quote $quote)
   {
      $quote->approved = false;
      $quote->reviewed = true;
      $quote->update();
      return back();
   }
   public function threadmanagement(Thread $thread, Request $request)
   {
      $this->validate($request, [
          'reason' => 'required|string',
      ]);
      $var = request('controlthread');
      if ($var=="1"){
         $thread->locked = !$thread->locked;
         $thread->save();
         if($thread->locked){
            Administration::create([
               'user_id' => Auth::id(),
               'operation' => '1',//1:锁帖
               'item_id' => $thread->id,
               'reason' => request('reason'),
            ]);
         }else{
            Administration::create([
               'user_id' => Auth::id(),
               'operation' => '2',//1:解锁
               'item_id' => $thread->id,
               'reason' => request('reason'),
            ]);
         }
         return redirect()->back()->with("success","已经成功处理该主题");
      }
      if ($var=="2"){
         $thread->public = !$thread->public;
         $thread->save();
         if(!$thread->public){
            Administration::create([
               'user_id' => Auth::id(),
               'operation' => '3',//3:转为私密
               'item_id' => $thread->id,
               'reason' => request('reason'),
            ]);
         }else{
            Administration::create([
               'user_id' => Auth::id(),
               'operation' => '4',//4:转为公开
               'item_id' => $thread->id,
               'reason' => request('reason'),
            ]);
         }
         return redirect()->back()->with("success","已经成功处理该主题");
      }
      if ($var=="3"){
         Administration::create([
            'user_id' => Auth::id(),
            'operation' => '5',//5:删帖
            'item_id' => $thread->id,
            'reason' => request('reason'),
         ]);
         $thread->delete();
         return redirect()->route('home')->with("success","已经删帖");
      }
      if ($var=="4"){
         Administration::create([
            'user_id' => Auth::id(),
            'operation' => '9',//转移版块
            'item_id' => $thread->id,
            'reason' => request('reason'),
         ]);
         $label = Label::findOrFail(request('label'));
         $channel = Channel::findOrFail(request('channel'));
         if(($label)&&($label->channel_id == $channel->id)){
            $thread->channel_id = $channel->id;
            $thread->label_id = $label->id;
            if($channel->channel_state!=1){
               $thread->book_id = 0;
            }else{
               if($thread->book_id==0){//这篇主题本来并不算文章,新建文章
                  $book = Book::create([
                     'thread_id' => $thread->id,
                     'original' => 2-$channel->id,
                     'book_status' => 0,
                     'book_length' => 0,
                     'lastaddedchapter_at' => Carbon::now(),
                  ]);
                  $tongren = App\Models\Tongren::create(
                      ['book_id' => $book->id]
                  );
               }else{
                  $book = Book::findOrFail($thread->book_id);
                  $book->original = 2-$channel->id;
                  $book->save();
                  if($channel->id == 2){
                     $tongren = App\Models\Tongren::firstOrCreate(['book_id' => $book->id]);
                  }
               }
            }
         }
         $thread->save();
         return redirect()->route('thread.show', $thread)->with("success","已经转移操作");
      }
      return redirect()->back()->with("danger","请选择操作类型（转换板块？）");
   }
   public function postmanagement(Post $post, Request $request)
   {
     $this->validate($request, [
         'reason' => 'required|string',
         'majia' => 'required|string|max:10'
     ]);
     $var = request('controlpost');//
     if ($var=="7"){//删帖
        Administration::create([
          'user_id' => Auth::id(),
          'operation' => '7',//:删回帖
          'item_id' => $post->id,
          'reason' => request('reason'),
        ]);
        if($post->chapter_id !=0){
          App\Models\Chapter::destroy($post->chapter_id);
        }
        $post->delete();
        return redirect()->back()->with("success","已经成功处理该贴");
     }
     if ($var=="10"){//修改马甲
       if (request('anonymous')=="1"){
         $post->anonymous = true;
         $post->majia = request('majia');
       }
       if (request('anonymous')=="2"){
         $post->anonymous = false;
       }
       $post->save();
        Administration::create([
          'user_id' => Auth::id(),
          'operation' => '10',//:修改马甲
          'item_id' => $post->id,
          'reason' => request('reason'),
        ]);
        return redirect()->back()->with("success","已经成功处理该回帖");
     }
     if ($var=="11"){//折叠
       $post->fold_state = !$post->fold_state;
       $post->save();
        Administration::create([
           'user_id' => Auth::id(),
           'operation' => ($post->fold_state? '11':'12'),//11 => '折叠帖子',12 => '解折帖子'
           'item_id' => $post->id,
           'reason' => request('reason'),
        ]);
        return redirect()->back()->with("success","已经成功处理该回帖");
     }
     return redirect()->back()->with("warning","什么都没做");
   }
   public function postcommentmanagement(PostComment $postcomment, Request $request)
   {
      $this->validate($request, [
          'reason' => 'required|string',
      ]);
      if(request("delete")){
         Administration::create([
            'user_id' => Auth::id(),
            'operation' => '8',//:删回帖
            'item_id' => $postcomment->id,
            'reason' => request('reason'),
         ]);
         $postcomment->delete();
         return redirect()->back()->with("success","已经成功处理该点评");
      }
      return redirect()->back()->with("warning","什么都没做");
   }
   public function advancedthreadform(Thread $thread)
   {
      $channels = Channel::all();
      $channels->load('labels');
      return view('admin.advanced_thread_form', compact('thread','channels'));
   }
   public function usermanagement(User $user, Request $request)
   {
     $this->validate($request, [
         'reason' => 'required|string',
         'days' => 'required|numeric',
         'hours' => 'required|numeric',
     ]);
     $var = request('controluser');//
     if ($var=="13"){//设置禁言时间
        Administration::create([
          'user_id' => Auth::id(),
          'operation' => '13',//:增加禁言时间
          'item_id' => $user->id,
          'reason' => request('reason'),
        ]);
        $user->no_posting = Carbon::now()->addDays(request('days'))->addHours(request('hours'));
        $user->save();
        return redirect()->back()->with("success","已经成功处理该用户");
     }
     if ($var=="14"){//解除禁言
        Administration::create([
          'user_id' => Auth::id(),
          'operation' => '14',//:增加禁言时间
          'item_id' => $user->id,
          'reason' => request('reason'),
        ]);
        $user->no_posting = Carbon::now();
        $user->save();
        return redirect()->back()->with("success","已经成功处理该用户");
     }

     return redirect()->back()->with("warning","什么都没做");
   }

   public function sendpublicmessageform()
   {
     return view('admin.send_publicmessage');
   }
   public function sendpublicmessage(Request $request)
   {
      $this->validate($request, [
          'body' => 'required|string|max:20000|min:10',
       ]);
       $receivers = User::all();
       $message_body = DB::table('message_bodies')->insertGetId([
            'content' => request('body'),
            'group_messaging' => 1,
         ]);
       foreach($receivers as $receiver){
         Message::create([
            'message_body' => $message_body,
            'poster_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'private' => false,
         ]);
         $receiver->increment('message_reminders');
       }
       return redirect()->back()->with('success','您已成功发布公共通知');

   }
}
