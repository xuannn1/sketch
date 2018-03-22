<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use Auth;
use App\Message;
use App\Models\User;

class MessagesController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }
   public function findposts($unread, $paginate)//1:show unread activities;0:show all activities
   {
      if ($unread ==1){
         return $posts = DB::table('activities')
         ->join('posts','activities.item_id','=','posts.id')
         ->join('users', 'posts.user_id', '=', 'users.id')
         ->join('threads','posts.thread_id','=','threads.id')
         ->where([['activities.type','=',1],['activities.seen','=',0],['posts.deleted_at', '=', null],['activities.user_id','=',Auth::id()]])
         ->select('posts.*', 'users.name','threads.title as thread_title','activities.seen')
         ->orderby('posts.created_at', 'desc')
         ->simplePaginate($paginate);
      }else{
         return $posts = DB::table('activities')
         ->join('posts','activities.item_id','=','posts.id')
         ->join('users', 'posts.user_id', '=', 'users.id')
         ->join('threads','posts.thread_id','=','threads.id')
         ->where([['activities.type','=',1],['posts.deleted_at', '=', null],['activities.user_id','=',Auth::id()]])
         ->select('posts.*', 'users.name','threads.title as thread_title','activities.seen')
         ->orderby('posts.created_at', 'desc')
         ->simplePaginate($paginate);
      }
   }
   public function findpostcomments($unread, $paginate)//1:show unread activities;0:show all activities
   {
      if ($unread ==1){
         return $postcomments = DB::table('activities')
         ->join('post_comments','activities.item_id','=','post_comments.id')
         ->join('users', 'post_comments.user_id', '=', 'users.id')
         ->join('posts','post_comments.post_id','=','posts.id')
         ->where([['activities.type','=',3],['activities.seen','=',0],['post_comments.deleted_at', '=', null],['activities.user_id','=',Auth::id()]])
         ->select('post_comments.*', 'users.name','posts.body as post_body','activities.seen')
         ->orderby('post_comments.created_at', 'desc')
         ->simplePaginate($paginate);
      }else{
         return $postcomments = DB::table('activities')
         ->join('post_comments','activities.item_id','=','post_comments.id')
         ->join('users', 'post_comments.user_id', '=', 'users.id')
         ->join('posts','post_comments.post_id','=','posts.id')
         ->where([['activities.type','=',3],['post_comments.deleted_at', '=', null],['activities.user_id','=',Auth::id()]])
         ->select('post_comments.*', 'users.name','posts.body as post_body','activities.seen')
         ->orderby('post_comments.created_at', 'desc')
         ->simplePaginate($paginate);
      }
   }
   public function findreplies($unread, $paginate)//1:show unread activities;0:show all activities
   {
      if ($unread ==1){
         return $posts = DB::table('activities')
         ->join('posts as replies','activities.item_id','=','replies.id')
         ->join('users', 'replies.user_id', '=', 'users.id')
         ->join('posts as originals','replies.reply_to_post_id','=','originals.id')
         ->where([['activities.type','=',2],['activities.seen','=',0],['replies.deleted_at', '=', null],['originals.user_id','=',Auth::id()]])
         ->select('replies.*', 'users.name','originals.body as original_body','activities.seen')
         ->orderby('replies.created_at', 'desc')
         ->simplePaginate($paginate);
      }else{
         return $posts = DB::table('activities')
         ->join('posts as replies','activities.item_id','=','replies.id')
         ->join('users', 'replies.user_id', '=', 'users.id')
         ->join('posts as originals','replies.reply_to_post_id','=','originals.id')
         ->where([['activities.type','=',2],['replies.deleted_at', '=', null],['originals.user_id','=',Auth::id()]])
         ->select('replies.*', 'users.name','originals.body as original_body','activities.seen')
         ->orderby('replies.created_at', 'desc')
         ->simplePaginate($paginate);
      }

   }



   public function findupvotes($unread, $paginate)//1:show unread activities;0:show all activities
   {
     if ($unread ==1){
        return $posts = DB::table('activities')
        ->join('vote_posts','vote_posts.id','=','activities.item_id')
        ->join('posts','vote_posts.post_id','=','posts.id')
        ->join('users as upvoter', 'vote_posts.user_id', '=', 'upvoter.id')
        ->join('users as poster', 'activities.user_id', '=', 'poster.id')
        ->join('threads','posts.thread_id','=','threads.id')
        ->where([
          ['activities.type','=',5],
          ['activities.seen','=',0],
          ['posts.deleted_at', '=', null],
          ['posts.user_id','=',Auth::id()],
          ['vote_posts.upvoted','=',1],
        ])
        ->select('posts.*', 'upvoter.name as upvoter_name', 'poster.name', 'threads.title as thread_title', 'activities.seen','vote_posts.user_id as upvoter_id','vote_posts.upvoted_at as upvoted_at')
        ->orderby('vote_posts.upvoted_at', 'desc')
        ->simplePaginate($paginate);
     }else{
        return $posts = DB::table('vote_posts')
        ->join('posts','vote_posts.post_id','=','posts.id')
        ->join('users as upvoter', 'vote_posts.user_id', '=', 'upvoter.id')
        ->join('users as poster', 'posts.user_id', '=', 'poster.id')
        ->join('threads','posts.thread_id','=','threads.id')
        ->where([
          ['posts.deleted_at', '=', null],
          ['posts.user_id','=',Auth::id()],
          ['vote_posts.upvoted','=',1],
        ])
        ->select('posts.*', 'upvoter.name as upvoter_name', 'poster.name', 'threads.title as thread_title','vote_posts.user_id as upvoter_id', 'vote_posts.upvoted_at as upvoted_at')
        ->orderby('vote_posts.upvoted_at', 'desc')
        ->simplePaginate($paginate);
     }
   }
   public function findmessages($unread, $paginate)//1:show unread activities;0:show all activities
   {
      if ($unread ==1){
        return $messages = DB::table('messages as m1')
        ->join('users','users.id','=','m1.poster_id')
        ->join('message_bodies','message_bodies.id','=','m1.message_body')
        ->where([['m1.seen','=',false],['m1.receiver_id','=', Auth::id()]])
        ->select('m1.*', 'users.name as poster_name','message_bodies.content','message_bodies.group_messaging')
        ->orderBy('m1.created_at', 'm1.desc')
        ->simplePaginate($paginate);
      }else{
         return $messages = DB::table('messages as m1')
         ->join('users','users.id','=','m1.poster_id')
         ->join('message_bodies','message_bodies.id','=','m1.message_body')
         ->where([['m1.receiver_id','=', Auth::id()]])
         ->select('m1.*', 'users.name as poster_name','message_bodies.content','message_bodies.group_messaging')
         ->orderBy('m1.created_at', 'm1.desc')
         ->simplePaginate($paginate);
      }
   }
   public function findmessages_combineduser($paginate)//展示收到信息-按收信人归类
   {
     return $messages = DB::table('messages as m1')
     ->join('users','users.id','=','m1.poster_id')
     ->join('message_bodies','message_bodies.id','=','m1.message_body')
     ->leftjoin('messages as m2', function($join){
       $join->on('m1.poster_id', '=', 'm2.poster_id');
       $join->on('m1.id', '<', 'm2.id');
     })->whereNull('m2.id')
     ->where('m1.receiver_id','=', Auth::id())
     ->select('m1.*', 'users.name as poster_name','message_bodies.content', 'message_bodies.group_messaging')
     ->orderBy('m1.created_at', 'm1.desc')
     ->simplePaginate($paginate);

   }
   public function findmessagessent($paginate)//展示所有已发送信息
   {
      return $messages = DB::table('message_bodies')
      ->join('messages as m1','message_bodies.id','=','m1.message_body')
      ->leftjoin('messages as m2', function($join){
        $join->on('m1.message_body', '=', 'm2.message_body');
        $join->on('m1.id', '<', 'm2.id');
      })->whereNull('m2.id')
      ->join('users','users.id','=','m1.receiver_id')
      ->where('m1.poster_id','=', Auth::id())
      ->select('m1.*', 'users.name as receiver_name','message_bodies.content','message_bodies.group_messaging')
      ->orderBy('m1.created_at', 'm1.desc')
      ->simplePaginate($paginate);
   }

   public function findconversation($id,$paginate)//show all messages with this particular user
   {
     return $messages = DB::table('messages')
       ->join('users as receivers', 'receivers.id','=', 'messages.receiver_id')
       ->join('users as posters', 'posters.id', '=', 'messages.poster_id')
       ->join('message_bodies','message_bodies.id','=','messages.message_body')
       ->where(function($query) use ($id)
           {
               $query->where('messages.receiver_id','=',$id)
                     ->where('messages.poster_id','=',Auth::id());
           })
       ->orWhere(function($query) use ($id)
           {
               $query->where('messages.poster_id','=',$id)
                     ->where('messages.receiver_id','=',Auth::id());
           })
       ->select('messages.*', 'receivers.name as receiver_name', 'posters.name as poster_name', 'message_bodies.content', 'message_bodies.group_messaging')
       ->orderBy('messages.created_at', 'desc')
       ->simplePaginate($paginate);
   }

   public function index()//1:show unread activities;0:show all activities
   {
      $messages = $this->findmessages(0, Config::get('constants.index_per_part'));
      $posts = $this->findposts(0, Config::get('constants.index_per_part'));
      $postcomments = $this->findpostcomments(0, Config::get('constants.index_per_part'));
      $replies = $this->findreplies(0, Config::get('constants.index_per_part'));
      $upvotes = $this->findupvotes(0, Config::get('constants.index_per_part'));
      return view('messages.index', compact('posts','postcomments','replies','messages','upvotes','group_messages'));
   }
   public function unread()//1:show unread activities;0:show all activities
   {
      $messages = $this->findmessages(1, Config::get('constants.index_per_part'));
      $posts = $this->findposts(1, Config::get('constants.index_per_part'));
      $postcomments = $this->findpostcomments(1, Config::get('constants.index_per_part'));
      $replies = $this->findreplies(1, Config::get('constants.index_per_part'));
      $upvotes = $this->findupvotes(1, Config::get('constants.index_per_part'));
      return view('messages.unread', compact('posts','postcomments','replies','messages','upvotes','group_messages'));
   }
   public function messagebox()//show all messages
   {
      $messages = $this->findmessages_combineduser(Config::get('constants.items_per_part'));
      $messages_sent = $this->findmessagessent(Config::get('constants.items_per_part'));
      return view('messages.messagebox', compact('messages','messages_sent','group_messages'));
   }

   public function posts()
   {
      $posts = $this->findposts(0, Config::get('constants.index_per_page'));
      return view('messages.posts', compact('posts'));
   }
   public function postcomments()
   {
      $postcomments = $this->findpostcomments(0, Config::get('constants.index_per_page'));
      return view('messages.postcomments', compact('postcomments'));
   }
   public function upvotes()
   {
      $upvotes = $this->findupvotes(0, Config::get('constants.index_per_page'));
      return view('messages.upvotes', compact('upvotes'));
   }
   public function replies()
   {
      $replies = $this->findreplies(0, Config::get('constants.index_per_page'));
      return view('messages.replies', compact('replies'));
   }
   public function messages()
   {
      $messages = $this->findmessages(0, Config::get('constants.index_per_page'));
      return view('messages.messages', compact('messages'));
   }
   public function messages_sent()
   {
      $messages_sent = $this->findmessagessent(Config::get('constants.index_per_page'));
      return view('messages.messages_sent', compact('messages_sent'));
   }
   public function clear()//make all reminders as seen
   {
      DB::table('activities')->where('user_id','=', Auth::id())->update(['seen'=>1]);
      DB::table('messages')->where('receiver_id','=', Auth::id())->update(['seen'=>1]);
      Auth::user()->post_reminders = 0;
      Auth::user()->reply_reminders = 0;
      Auth::user()->postcomment_reminders = 0;
      Auth::user()->message_reminders = 0;
      Auth::user()->upvote_reminders = 0;
      Auth::user()->save();
      return redirect()->back()->with("success", "您已清除所有未读消息提醒");
   }

   public function conversation(User $user, $is_group_messaging){
     $messages = $this->findconversation($user->id, Config::get('constants.index_per_page'), $is_group_messaging);
     return view('messages.conversations', compact('messages','user','is_group_messaging'));
   }
   public function create(User $user)
   {
      return view('messages.create', compact('user'));
   }

   public function store(User $user, Request $request)
   {
      if((Auth::user()->admin)||($user->isFollowing(Auth::id()))||(($user->receive_messages_from_stranger)&&(Auth::user()->message_limit>0))){
         $this->validate($request, [
             'body' => 'required|string|max:20000|min:10',
          ]);
         $message_body = DB::table('message_bodies')->insertGetId(
            ['content' => request('body')]
         );
         Message::create([
            'message_body' => $message_body,
            'poster_id' => Auth::id(),
            'receiver_id' => $user->id,
            'private' => false,
         ]);
         $user->increment('message_reminders');
         if (!Auth::user()->isFollowing($user->id)) {
             Auth::user()->follow($user->id);
         }
         if ((!Auth::user()->admin)&&(!$user->isFollowing(Auth::id()))){
            Auth::user()->decrement('message_limit');
         }
         return redirect(route('messages.messages'))->with("success", "您已成功发送私信");
      }else{
         return redirect()->back()->with("danger", "您不能发送该私信");
      }
   }
   public function cancelreceivemessagesfromstrangers()
   {
      $user = Auth::user();
      $user->receive_messages_from_stranger=false;
      $user->save();
      return 'works';
   }
   public function receivemessagesfromstrangers()
   {
      $user = Auth::user();
      $user->receive_messages_from_stranger=true;
      $user->save();
      return 'works';
   }
   public function cancelreceiveupvotereminders()
   {
      $user = Auth::user();
      $user->no_upvote_reminders=1;
      $user->save();
      return 'works';
   }
   public function receiveupvotereminders()
   {
      $user = Auth::user();
      $user->no_upvote_reminders = 0;
      $user->save();
      return 'works';
   }
}
