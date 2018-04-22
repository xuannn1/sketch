<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sosadfun\Traits\BookTraits;
use App\Sosadfun\Traits\ThreadTraits;
use Auth;
use Hash;
use App\Models\User;
use Carbon\Carbon;

class UsersController extends Controller
{
    use BookTraits;
    use ThreadTraits;
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => ['edit', 'update', 'destroy', 'qiandao'],
        ]);
    }
    public function findbooks($id, $paginate)
    {
        $query = $this->join_book_tables();
        $query->where('threads.user_id','=',$id);//属于这个人
        //未登陆，看不见边缘文章
        if(!Auth::check()){$query->where('threads.bianyuan','=',0);}
        //假如未登陆，或者既不是管理员也不是本人，则不能看私密文章，不能看匿名文章
        if((!Auth::check())||(($id !== Auth::id())&&(!Auth::user()->admin))){
            $query->where('threads.public','=',1)
            ->where('threads.anonymous','=',0);
        }
        //已删除的也不能看
        $query->where('threads.deleted_at', '=', null);
        $books = $this->return_book_fields($query)
        ->orderby('books.lastaddedchapter_at', 'desc')
        ->simplePaginate($paginate);
        return $books;
    }

    public function findthreads($id, $paginate, $group)
    {
        $query = $this->join_no_book_thread_tables();
        $query->where('threads.user_id','=',$id);//属于这个人
        //未登陆，看不见边缘文章
        if(!Auth::check()){$query->where('threads.bianyuan','=',0);}
        //假如未登陆，或者既不是管理员也不是本人，则不能看私密文章，不能看匿名文章
        if((!Auth::check())||(($id !== Auth::id())&&(!Auth::user()->admin))){
            $query->where('threads.public','=',1)
            ->where('threads.anonymous','=',0);
        }
        //已删除的也不能看
        $query->where('threads.deleted_at', '=', null);
        //不能是图书，只能是讨论帖
        $query->where('threads.book_id', '=', 0);
        $query->where('channel.channel_state', '<', $group);
        $threads = $this->return_no_book_thread_fields($query)
        ->orderby('threads.lastresponded_at', 'desc')
        ->simplePaginate($paginate);
        return $threads;
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
            return view('users.show', compact('user','books','threads','posts','statuses','upvotes'))->with('show_as_collections',false)->with('show_channel',1);
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
            return view('users.showthreads', compact('user','threads','show','collections'))->with('show_as_collections',false)->with('show_channel',1);
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
            $message = DB::transaction(function () use($user){
                if ($user->lastrewarded_at > Carbon::now()->subdays(2)->toDateTimeString()) {
                    $user->increment('continued_qiandao');
                    if($user->continued_qiandao>$user->maximum_qiandao){$user->maximum_qiandao = $user->continued_qiandao;}
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
                $user->increment('experience_points', 5*$reward_base);
                $user->message_limit = $user->user_level;
                $user->save();
                if($user->checklevelup()){
                    $message .="您的个人等级已提高!";
                }
                return $message;
            });
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
