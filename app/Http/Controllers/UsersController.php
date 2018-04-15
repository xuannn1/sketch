<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use Auth;
use Hash;
use App\Models\User;
use Carbon\Carbon;

class UsersController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth', [
        'only' => ['edit', 'update', 'destroy', 'qiandao'],
      ]);
   }
   public function findbooks($id, $paginate)
   {
      if ($id == Auth::id()){
         return $books = DB::table('threads')
         ->join('books', 'threads.book_id', '=', 'books.id')
         ->join('users', 'threads.user_id', '=', 'users.id')
         ->join('labels', 'threads.label_id', '=', 'labels.id')
         ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
         ->where([['threads.deleted_at', '=', null],['threads.user_id','=',$id]])
         ->select('books.*', 'threads.*', 'users.name','labels.labelname','chapters.title as last_chapter_title', 'chapters.responded as last_chapter_responded')
         ->orderby('books.lastaddedchapter_at', 'desc')
         ->simplePaginate($paginate);
      }else{
         return $books = DB::table('threads')
         ->join('books', 'threads.book_id', '=', 'books.id')
         ->join('users', 'threads.user_id', '=', 'users.id')
         ->join('labels', 'threads.label_id', '=', 'labels.id')
         ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
         ->where([['threads.deleted_at', '=', null],['threads.user_id','=',$id],['threads.anonymous','=',0],['threads.public','=',1]])
         ->select('books.*', 'threads.*', 'users.name','labels.labelname','chapters.title as last_chapter_title', 'chapters.responded as last_chapter_responded')
         ->orderby('books.lastaddedchapter_at', 'desc')
         ->simplePaginate($paginate);
      }
   }

   public function findthreads($id, $paginate, $group)
   {
      if ($id == Auth::id()){
         return $threads = DB::table('threads')
         ->join('users', 'threads.user_id', '=', 'users.id')
         ->join('labels', 'threads.label_id', '=', 'labels.id')
         ->join('channels', 'threads.channel_id','=','channels.id')
         ->leftjoin('posts','threads.last_post_id','=', 'posts.id')
         ->where([['threads.book_id', '=', 0],['threads.deleted_at', '=', null],['threads.user_id','=',$id]])
         ->select('channels.channelname','threads.*', 'users.name','labels.labelname','posts.body as last_post_body')
         ->orderby('threads.lastresponded_at', 'desc')
         ->simplePaginate($paginate);
      }else{
         return $threads = DB::table('threads')
         ->join('users', 'threads.user_id', '=', 'users.id')
         ->join('labels', 'threads.label_id', '=', 'labels.id')
         ->join('channels', 'threads.channel_id','=','channels.id')
         ->leftjoin('posts','threads.last_post_id','=', 'posts.id')
         ->where([['threads.book_id', '=', 0],['threads.deleted_at', '=', null],['threads.anonymous','=',0],['threads.public','=',1],['threads.user_id','=',$id],['channels.channel_state','<',$group]])
         ->select('channels.channelname','threads.*', 'users.name','labels.labelname','posts.body as last_post_body')
         ->orderby('threads.lastresponded_at', 'desc')
         ->simplePaginate($paginate);
      }
   }

   public function findlongcomments($id, $paginate, $group)
   //需要调整
   {
      if ($id == Auth::id()){
         return $posts = DB::table('posts')
         ->join('users','users.id','=','posts.user_id')
         ->join('threads','threads.id','=','posts.thread_id')
         ->where([['posts.user_id','=',$id],['posts.long_comment','=',1],['posts.deleted_at','=',null]])
         ->select('posts.*','threads.title as thread_title', 'users.name')
         ->orderBy('posts.created_at', 'desc')
         ->simplePaginate($paginate);
      }else{
         return $posts = DB::table('posts')
         ->join('users','users.id','=','posts.user_id')
         ->join('threads','threads.id','=','posts.thread_id')
         ->join('channels', 'threads.channel_id','=','channels.id')
         ->where([['posts.user_id','=',$id],['posts.anonymous','=',0],['posts.long_comment','=',1],['posts.deleted_at','=',null],['channels.channel_state','<',$group]])
         ->select('posts.*','threads.title as thread_title', 'users.name')
         ->orderBy('posts.created_at', 'desc')
         ->simplePaginate($paginate);
      }
   }
   public function findstatuses($id, $paginate)
   {
      return $statuses = DB::table('statuses')
      ->join('users','users.id','=','statuses.user_id')
      ->where('statuses.user_id','=',$id)
      ->select('statuses.*','users.name')
      ->orderBy('statuses.created_at', 'desc')
      ->simplePaginate($paginate);
   }
   public function findupvotes($id, $paginate, $group)
   {
     if ($id == Auth::id()){
       return $upvotes = DB::table('vote_posts')
       ->join('posts','vote_posts.post_id','=','posts.id')
       ->join('users as upvoter', 'vote_posts.user_id', '=', 'upvoter.id')
       ->join('users as poster', 'posts.user_id', '=', 'poster.id')
       ->join('threads','posts.thread_id','=','threads.id')
       ->join('channels', 'threads.channel_id','=','channels.id')
       ->where([
         ['posts.deleted_at', '=', null],
         ['vote_posts.user_id','=',$id],
         ['vote_posts.upvoted','=',1],
         ['channels.channel_state','<',$group]
       ])
       ->select('posts.*', 'upvoter.name as upvoter_name', 'poster.name', 'threads.title as thread_title','vote_posts.user_id as upvoter_id','vote_posts.upvoted_at as upvoted_at')
       ->orderby('vote_posts.upvoted_at', 'desc')
       ->simplePaginate($paginate);
     }else{
       return $upvotes = DB::table('vote_posts')
       ->join('posts','vote_posts.post_id','=','posts.id')
       ->join('users as upvoter', 'vote_posts.user_id', '=', 'upvoter.id')
       ->join('users as poster', 'posts.user_id', '=', 'poster.id')
       ->join('threads','posts.thread_id','=','threads.id')
       ->join('channels', 'threads.channel_id','=','channels.id')
       ->where([
         ['posts.deleted_at', '=', null],
         ['vote_posts.user_id','=',$id],
         ['vote_posts.upvoted','=',1],
         ['threads.bianyuan','=',0],
         ['channels.channel_state','<',$group]
       ])
       ->select('posts.*', 'upvoter.name as upvoter_name', 'poster.name', 'threads.title as thread_title','vote_posts.user_id as upvoter_id','vote_posts.upvoted_at as upvoted_at')
       ->orderby('vote_posts.upvoted_at', 'desc')
       ->simplePaginate($paginate);
     }

   }

   public function show($id)
   {
      $user = User::find($id);
      if ($user){
         $group = Auth::check() ? Auth::user()->group : 10;
         $books=$this->findbooks($id,config('constants.index_per_part'));
         $threads=$this->findthreads($id,config('constants.index_per_part'), $group);
         $posts=$this->findlongcomments($id,config('constants.index_per_part'), $group);
         $statuses=$this->findstatuses($id,config('constants.index_per_part'));
         $upvotes=$this->findupvotes($id,config('constants.index_per_part'), $group);
         return view('users.show', compact('user','books','threads','posts','statuses','upvotes'))->with('show_as_collections',false);
      }else{
         return redirect()->route('error', ['error_code' => '404']);
      }
   }

   public function showbooks($id)
   {
      $user = User::find($id);
      if ($user){
         $books=$this->findbooks($id,config('constants.index_per_page'));
         $book_info = config('constants.book_info');
         $collections = false;
         return view('users.showbooks', compact('user','book_info','books','collections'))->with('show_as_collections',false);
      }else{
         return redirect()->route('error', ['error_code' => '404']);
      }
   }

   public function showlongcomments($id)
   {
      $user = User::find($id);
      $group = Auth::check() ? Auth::user()->group : 10;
      if ($user){
         $posts=$this->findlongcomments($id,config('constants.index_per_page'),$group);
         return view('users.showlongcomments', compact('user','posts'));
      }else{
         return redirect()->route('error', ['error_code' => '404']);
      }
   }

   public function showthreads($id)
   {
      $user = User::find($id);
      if ($user){
         $group = Auth::check() ? Auth::user()->group : 10;
         $threads=$this->findthreads($id,config('constants.index_per_page'),$group);
         $show = [
            'channel' => false,
            'label' => false,
         ];
         $collections = false;
         return view('users.showthreads', compact('user','threads','show','collections'))->with('show_as_collections',false);
      }else{
         return redirect()->route('error', ['error_code' => '404']);
      }
   }
   public function showstatuses($id)
   {
      $user = User::find($id);
      if ($user){
         $statuses=$this->findstatuses($id,config('constants.index_per_page'));
         $collections = false;
         return view('users.showstatuses', compact('user','statuses','collections'))->with('show_as_collections',false);
      }else{
         return redirect()->route('error', ['error_code' => '404']);
      }
   }

   public function showupvotes($id){
     $user = User::find($id);
     if ($user){
        $group = Auth::check() ? Auth::user()->group : 10;
        $posts=$this->findupvotes($id,config('constants.index_per_page'), $group);
        $collections = false;
        return view('users.showupvotes', compact('user','posts','collections'));
     }else{
        return redirect()->route('error', ['error_code' => '404']);
     }
   }

   public function edit()
   {
      $user = Auth::user();
      return view('users.edit', compact('user'));
   }
   public function update(Request $request)
   {
      //dd($request);
      $user = Auth::user();
      if(Hash::check(request('old-password'), $user->password)) {
         // if ((request('name'))&&(request('name')!=$user->name)){//要修改名字
         //    $this->validate($request, [
         //       'name' => 'required|string|alpha_dash|max:10|unique:users',
         //    ]);
         // }
         $this->validate($request, [
            'introduction' => 'string|nullable|max:2000',
         ]);
         if (request('password')){//要修改密码
            $this->validate($request, [
               'password' => 'required|min:8|max:16|confirmed',
            ]);
            $user->update(['password' => bcrypt(request('password'))]);
         }
         $user->update([
            // 'name' => request('name'),
            'introduction' => request('introduction'),
         ]);
         return redirect()->route('user.show', Auth::id())->with("success", "您已成功修改个人资料");
      }else{
         return back()->with("danger", "您的旧密码输入错误");
      }
   }
   public function qiandao()
   {
      $user = Auth::user();
      if ($user->lastrewarded_at <= Carbon::today()->toDateTimeString())
      {
         if ($user->lastrewarded_at > Carbon::today()->subdays(1)->toDateTimeString()) {
             $user->increment('continued_qiandao');
         }else{
            $user->continued_qiandao=1;
         }
         $user->lastrewarded_at = Carbon::now();
         $message = "您已成功签到！连续签到".$user->continued_qiandao."天！";
         $reward_base = 1;
         if(($user->continued_qiandao>=5)&&($user->continued_qiandao%5==0)){
            $reward_base = intval($user->continued_qiandao/10)+2;
            $message .="您获得了特殊奖励！";
         }
         $user->increment('xianyu', 1*$reward_base);
         $user->increment('shengfan', 5*$reward_base);
         $user->increment('jifen', 5*$reward_base);
         $user->message_limit = $user->user_level;
         $user->save();
         if($user->checklevelup()){
            $message .="您的个人等级已提高!";
         }
         return back()->with("success", $message);
      }else{
         return back()->with("info", "您已领取奖励，请勿重复签到");
      }
   }

   public function followings($id)
    {
      $user = User::findOrFail($id);
      $users = $user->followings()->paginate(config('constants.index_per_page'));
      $title = '关注的人';
      return view('users.showfollows', compact('user','users','title'));
    }

    public function followers($id)
    {
      $user = User::findOrFail($id);
      $users = $user->followers()->paginate(config('constants.index_per_page'));
      $title = '粉丝';
      return view('users.showfollows', compact('user','users','title'));
    }
   public function index()
   {
      $users = User::orderBy('lastrewarded_at','desc')->paginate(config('constants.index_per_page'));
      return view('users.index_all', compact('users'));
   }

}
