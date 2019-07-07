<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sosadfun\Traits\BookTraits;
use App\Sosadfun\Traits\ThreadTraits;
use App\Sosadfun\Traits\AdministrationTraits;
use Auth;
use Hash;
use App\Models\User;
use Carbon\Carbon;
use App\Models\EmailModifyHistory;
use App\Models\PasswordReset;
use Mail;

class UsersController extends Controller
{
    use BookTraits;
    use ThreadTraits;
    use AdministrationTraits;

    public function __construct()
    {
        $this->middleware('auth', [
            'only' => ['edit', 'update', 'edit_email', 'update_email', 'destroy', 'qiandao', 'send_email_confirmation'],
        ]);
    }
    public function findbooks($id, $paginate)
    {
        $query = $this->join_book_tables();
        $query->where('threads.deleted_at', '=', null);//已删除的也不能看
        $query->where('threads.book_id', '>', 0);//只能是图书
        $query->where('threads.user_id','=',$id);//属于这个人

        if((Auth::check())&&(($id == Auth::id())||(Auth::user()->admin))){//管理员或本人，可见私密+匿名。未登录用户，或其他人，只能看到全部文章
        }else{
            $query->where('threads.public','=',1)
            ->where('threads.anonymous','=',0);
        }
        $books = $this->return_book_fields($query)
        ->orderBy('books.lastaddedchapter_at', 'desc')
        ->simplePaginate($paginate);
        return $books;
    }

    public function findthreads($id, $paginate, $group)
    {
        $query = $this->join_no_book_thread_tables();
        $query->where('threads.deleted_at', '=', null);//已删除的也不能看
        $query->where('threads.book_id', '=', 0);//不能是图书，只能是讨论帖
        $query->where('threads.user_id','=',$id);//属于这个人

        if((Auth::check())&&(($id == Auth::id())||(Auth::user()->admin))){//管理员或本人，可见私密+匿名。未登录用户，或其他人，只能看到全部文章
        }else{//未登陆，看不见私密文章，匿名文章
            $query->where('threads.public','=',1)
            ->where('threads.anonymous','=',0);
            $query->where('channels.channel_state', '<', $group);//权限限制
        }

        $threads = $this->return_no_book_thread_fields($query)
        ->orderBy('threads.lastresponded_at', 'desc')
        ->simplePaginate($paginate);
        return $threads;
    }

    public function findcomments($id, $paginate, $group)
    //需要调整
    {
        if ((Auth::check())&&($id === Auth::id()||Auth::user()->admin)){
            return $posts = DB::table('posts')
            ->join('users','users.id','=','posts.user_id')
            ->join('threads', function($join) {
	               $join->on([['posts.thread_id', '=', 'threads.id'],['posts.id','<>','threads.post_id']]);
            })
            ->where([['posts.id','<>','threads.post_id'], ['posts.user_id','=',$id], ['posts.maintext','=',0], ['posts.deleted_at','=',null]])
            ->select('posts.*','threads.title as thread_title', 'users.name')
            ->orderBy('posts.created_at', 'desc')
            ->simplePaginate($paginate);
        }else{
            return $posts = DB::table('posts')
            ->join('users','users.id','=','posts.user_id')
            ->join('threads', function($join) {
	               $join->on([['posts.thread_id', '=', 'threads.id'],['posts.id','<>','threads.post_id']]);
            })
            ->join('channels', 'threads.channel_id','=','channels.id')
            ->where([['posts.user_id','=',$id], ['posts.maintext','=',0],['posts.anonymous','=',0], ['posts.deleted_at','=',null],['channels.channel_state','<',$group]])
            ->select('posts.*', 'threads.title as thread_title', 'users.name')
            ->orderBy('posts.created_at', 'desc')
            ->simplePaginate($paginate);

        }
    }
    public function findstatuses($id, $paginate)
    {
        return $statuses = DB::table('statuses')
        ->join('users','users.id','=','statuses.user_id')
        ->where('statuses.user_id','=',$id)
        ->where('statuses.deleted_at','=',null)
        ->select('statuses.*','users.name')
        ->orderBy('statuses.created_at', 'desc')
        ->simplePaginate($paginate);
    }
    public function findupvotes($id, $paginate, $group)
    {
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
        ->orderBy('vote_posts.upvoted_at', 'desc')
        ->simplePaginate($paginate);
    }

    public function findxianyus($id, $paginate, $group)
    {
        $query = $this->join_thread_tables();
        $query->join('xianyus','xianyus.thread_id','=','threads.id')
        ->where([
            ['threads.deleted_at', '=', null],
            ['threads.public', '=', 1],
            ['channels.channel_state','<',$group],
            ['xianyus.user_id','=',$id]
        ]);
        $xianyus = $this->return_thread_fields($query)
        ->orderBy('xianyus.created_at', 'desc')
        ->simplePaginate($paginate);
        return $xianyus;
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user){
            $group = Auth::check() ? Auth::user()->group : 10;
            $books=$this->findbooks($id,config('constants.index_per_part'));
            $threads=$this->findthreads($id,config('constants.index_per_part'), $group);
            $posts=$this->findcomments($id,config('constants.index_per_part'), $group);
            $statuses=$this->findstatuses($id,config('constants.index_per_part'));
            $upvotes=$this->findupvotes($id,config('constants.index_per_part'), $group);
            $xianyus=$this->findxianyus($id,config('constants.index_per_part'), $group);
            $records = [];
            if((Auth::check())&&(Auth::user()->admin)){
                $records=$this->findAdminRecords($id,config('constants.items_per_part'));
            }
            $admin_operation = config('constants.administrations');
            return view('users.show', compact('user','books','threads','posts','statuses','upvotes','xianyus','records','admin_operation'))->with('show_as_collections',false)->with('show_channel',1)->with('as_longcomments',0);
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

    public function showcomments($id)
    {
        $user = User::find($id);
        $group = Auth::check() ? Auth::user()->group : 10;
        if ($user){
            $posts=$this->findcomments($id,config('constants.index_per_page'), $group);
            return view('users.showcomments', compact('user','posts'))->with('as_longcomments',0);
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
            $upvotes=$this->findupvotes($id,config('constants.index_per_page'), $group);
            $collections = false;
            return view('users.showupvotes', compact('user','upvotes','collections'))->with('as_longcomments',0);
        }else{
            return redirect()->route('error', ['error_code' => '404']);
        }
    }

    public function showxianyus($id){
        $user = User::find($id);
        if ($user){
            $group = Auth::check() ? Auth::user()->group : 10;
            $xianyus=$this->findxianyus($id,config('constants.index_per_page'), $group);
            $collections = false;
            return view('users.showxianyus', compact('user','xianyus','collections'))->with('show_as_collections',false)->with('show_channel',1);
        }else{
            return redirect()->route('error', ['error_code' => '404']);
        }
    }

    public function showrecords($id){
        $user = User::find($id);
        if($user) {
            $records=$this->findAdminRecords($id,config('constants.index_per_page'));
            $admin_operation = config('constants.administrations');
            return view('users.showrecords', compact('user','records','admin_operation'));
        }else {
            return redirect()->route('error', ['error_code' => '404']);
        }
    }

    public function edit()
    {
        $user = Auth::user();
        $last_email = PasswordReset::where('email','=',$user->email)->latest()->first();
        $email_confirmed = $user->activation_token ? false:true;
        return view('users.edit', compact('user','last_email','email_confirmed'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'introduction' => 'string|nullable|max:2000',
        ]);
        $user->update([
            'introduction' => request('introduction'),
        ]);
        return redirect()->route('user.show', Auth::id())->with("success", "您已成功修改个人资料");
    }

    public function edit_email()
    {
        $user = Auth::user();
        $previous_history_counts = EmailModifyHistory::where('user_id','=',Auth::id())->where('created_at','>',Carbon::now()->subMonth(1)->toDateTimeString())->count();
        return view('users.edit_email', compact('user','previous_history_counts'));
    }

    public function update_email(Request $request)
    {
        $user = Auth::user();
        if(Hash::check(request('old-password'), $user->password)) {
            $this->validate($request, [
                'email' => 'required|string|email|max:255|unique:users|confirmed',
            ]);
            $old_email = $user->email;
            $previous_history_counts = EmailModifyHistory::where('user_id','=',Auth::id())->where('created_at','>',Carbon::now()->subMonth(1)->toDateTimeString())->count();
            if ($previous_history_counts>=config('constants.monthly_email_resets')){
                return redirect()->back()->with('warning','一个月内只能修改'.config('constants.monthly_email_resets').'次邮箱。');
            }
            EmailModifyHistory::create([
                'old-email' => $old_email,
                'new-email' => request('email'),
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
            ]);
            $user->email = request('email');
            $user->activation_token = str_random(30);
            $user->save();
            return redirect()->route('users.edit', Auth::id())->with("success", "您已成功修改个人资料");
        }
        return back()->with("danger", "您的旧密码输入错误");
    }


    public function edit_password(){
        $user = Auth::user();
        return view('users.edit_password', compact('user'));
    }

    public function update_password(Request $request){
        $user = Auth::user();
        if(Hash::check(request('old-password'), $user->password)) {
            $this->validate($request, [
                'password' => 'required|min:8|max:16|confirmed',
            ]);
            $user->update(['password' => bcrypt(request('password'))]);
            return redirect()->route('users.edit', Auth::id())->with("success", "您已成功修改个人密码");
        }
        return back()->with("danger", "您的旧密码输入错误");
    }

    public function qiandao()
    {
        $user = Auth::user();
        $info = $user->info;
        if ($user->qiandao_at <= Carbon::today()->subHours(2)->toDateTimeString())
        {
            $message = DB::transaction(function () use($user, $info){
                if ($user->qiandao_at > Carbon::now()->subdays(2)->toDateTimeString()) {
                    $info->continued_qiandao+=1;
                    if($info->continued_qiandao>$info->max_qiandao){$info->max_qiandao = $info->continued_qiandao;}
                }else{
                    $info->continued_qiandao=1;
                }
                $user->qiandao_at = Carbon::now();
                $message = "您已成功签到！连续签到".$info->continued_qiandao."天！";
                $reward_base = 1;
                if(($info->continued_qiandao>=5)&&($info->continued_qiandao%5==0)){
                    $reward_base = intval($info->continued_qiandao/10)+2;
                    if($reward_base > 10){$reward_base = 10;}
                    $message .="您获得了特殊奖励！";
                }
                $info->reward(5*$reward_base, 5*$reward_base, 5*$reward_base, 1*$reward_base, 0);
                $info->message_limit = $user->level;
                $info->list_limit = $user->level;
                $info->save();
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
        return view('statuses.users_index', compact('users'))->with('active',2);
    }

}
