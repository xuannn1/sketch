<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sosadfun\Traits\AdministrationTraits;
use Auth;
use Hash;
use App\Models\User;
use Carbon;
use App\Models\EmailModifyHistory;
use App\Models\PasswordReset;
use Mail;
use CacheUser;
use Cache;
use ConstantObjects;

class UsersController extends Controller
{

    use AdministrationTraits;

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['followers','followings','index','show','threads','lists','statuses'],
        ]);
    }

    public function edit()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $last_email_sent = PasswordReset::where('email','=',$user->email)->latest()->first()->created_at;
        $email_confirmed = $info->activation_token ? false:true;
        return view('users.edit', compact('user', 'info','last_email_sent','email_confirmed'));
    }

    public function edit_email()
    {
        $user = Auth::user();
        $previous_history_counts = EmailModifyHistory::where('user_id','=',Auth::id())->where('created_at','>',Carbon::now()->subMonth(1)->toDateTimeString())->count();
        return view('user.edit_email', compact('user','previous_history_counts'));
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
        return view('user.edit_password', compact('user'));
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

    public function edit_introduction()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::Aintro():null;
        return view('users.edit_introduction', compact('user','info','intro'));
    }

    public function update_introduction(Request $request)
    {
        $this->validate($request, [
            'brief_intro' => 'required|string|max:45',
            'introduction' => 'required|string|max:2000',
        ]);
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::Aintro():null;

        $info->update([
            'brief_intro'=>$request->brief_intro,
        ]);
        if($intro){
            $intro->update([
                'body'=>$request->introduction,
                'edited_at' => Carbon::now(),
            ]);
        }else{
            App\Models\UserIntro::create([
                'user_id' => $user->id,
                'body'=>$request->introduction,
                'edited_at' => Carbon::now(),
            ]);
        }
        return redirect()->route('user.show', $user->id);
    }

    public function qiandao()
    {
        $user = Auth::user();
        $info = $user->info;
        if ($user->qiandao_at <= Carbon::today()->subHours(2)->toDateTimeString())
        {
            $message = DB::transaction(function () use($user, $info){
                if ($user->qiandao_at > Carbon::now()->subdays(2)) {
                    $info->qiandao_continued+=1;
                    if($info->qiandao_continued>$info->qiandao_max){$info->qiandao_max = $info->qiandao_continued;}
                }else{
                    $info->qiandao_continued=1;
                }
                $user->qiandao_at = Carbon::now();
                $message = "您已成功签到！连续签到".$info->continued_qiandao."天！";
                $reward_base = 1;
                if(($info->continued_qiandao>=5)&&($info->continued_qiandao%5==0)){
                    $reward_base = intval($info->continued_qiandao/10)+2;
                    if($reward_base > 10){$reward_base = 10;}
                    $message .="您获得了特殊奖励！";
                }
                $info->rewardData(5*$reward_base, 5*$reward_base, 5*$reward_base, 1*$reward_base, 0);
                $info->message_limit = $user->level-4;
                $info->list_limit = $user->level-4;
                $info->save();
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
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $intro = $info->has_intro? CacheUser::Aintro():null;
        $users = $user->followings()->paginate(config('preference.users_per_page'));
        $users->load('info','title');

        return view('users.show_follow', compact('user', 'info', 'intro', 'users'))->with(['follow_title'=>'关注的人']);
    }

    public function followers($id)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $intro = $info->has_intro? CacheUser::Aintro():null;
        $users = $user->followers()->paginate(config('preference.users_per_page'));
        $users->load('info','title');
        $title = '粉丝';
        return view('users.show_follow', compact('user', 'info', 'intro', 'users'))->with(['follow_title'=>'粉丝']);
    }
    public function index(Request $request)
    {
        $queryid = 'UserIndex.'
        .url('/')
        .(is_numeric($request->page)? 'P'.$request->page:'P1');

        $users = Cache::remember($queryid, 10, function () use($request) {
            return User::with('title','info')
            ->orderBy('qiandao_at','desc')
            ->paginate(config('preference.users_per_page'))
            ->appends($request->only('page'));
        });

        return view('statuses.user_index', compact('users'))->with(['status_tab'=>'user']);
    }

    public function center()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $intro = $info->has_intro? CacheUser::Aintro():null;

        return view('users.center', compact('user','info','intro'));
    }

    public function show($id, Request $request)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::intro($id):null;

        if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
            $threads = \App\Models\Thread::with('tags','author','last_post','last_component')
            ->withUser($id)
            ->withType('book')
            ->ordered('latest_add_component')
            ->paginate(config('preference.threads_per_page'));
        }else{
            $queryid = 'UserBook.'
            .url('/')
            .$id
            .(is_numeric($request->page)? 'P'.$request->page:'P1');

            $threads = Cache::remember($queryid, 10, function () use($request, $id) {
                return \App\Models\Thread::with('tags','author','last_post','last_component')
                ->withUser($id)
                ->withType('book')
                ->isPublic()
                ->inPublicChannel()
                ->withAnonymous('none_anonymous_only')
                ->ordered('latest_add_component')
                ->paginate(config('preference.threads_per_page'))
                ->appends($request->only('page'));
            });
        }

        return view('users.show', compact('user','info','intro','threads'))->with(['show_user_tab'=>'book', 'user_title'=>'书籍']);
    }

    public function threads($id, Request $request)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::intro($id):null;

        if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
            $threads = \App\Models\Thread::with('tags','author','last_post','last_component')
            ->withUser($id)
            ->withType('thread')
            ->ordered()
            ->paginate(config('preference.threads_per_page'));
        }else{
            $queryid = 'UserThread.'
            .url('/')
            .$id
            .(is_numeric($request->page)? 'P'.$request->page:'P1');

            $threads = Cache::remember($queryid, 10, function () use($request, $id) {
                return \App\Models\Thread::with('tags','author','last_post','last_component')
                ->withUser($id)
                ->withType('thread')
                ->isPublic()
                ->inPublicChannel()
                ->withAnonymous('none_anonymous_only')
                ->ordered()
                ->paginate(config('preference.threads_per_page'))
                ->appends($request->only('page'));
            });
        }
        return view('users.show', compact('user','info','intro','threads'))->with(['show_user_tab'=>'thread','user_title'=>'主题']);
    }

    public function lists($id, Request $request)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::intro($id):null;

        if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
            $threads = \App\Models\Thread::with('tags','author','last_post','last_component')
            ->withUser($id)
            ->withType('list')
            ->ordered()
            ->paginate(config('preference.threads_per_page'));
        }else{
            $queryid = 'UserList.'
            .url('/')
            .$id
            .(is_numeric($request->page)? 'P'.$request->page:'P1');

            $threads = Cache::remember($queryid, 10, function () use($request, $id) {
                return \App\Models\Thread::with('tags','author','last_post','last_component')
                ->withUser($id)
                ->withType('list')
                ->isPublic()
                ->inPublicChannel()
                ->withAnonymous('none_anonymous_only')
                ->ordered()
                ->paginate(config('preference.threads_per_page'))
                ->appends($request->only('page'));
            });
        }
        return view('users.show', compact('user','info','intro','threads'))->with(['show_user_tab'=>'list','user_title'=>'清单']);
    }

    public function boxes($id, Request $request)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::intro($id):null;

        if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
            $threads = \App\Models\Thread::with('tags','author','last_post','last_component')
            ->withUser($id)
            ->withType('box')
            ->ordered()
            ->paginate(config('preference.threads_per_page'));
        }else{
            $queryid = 'UserBox.'
            .url('/')
            .$id
            .(is_numeric($request->page)? 'P'.$request->page:'P1');

            $threads = Cache::remember($queryid, 10, function () use($request, $id) {
                return \App\Models\Thread::with('tags','author','last_post','last_component')
                ->withUser($id)
                ->withType('box')
                ->isPublic()
                ->inPublicChannel()
                ->withAnonymous('none_anonymous_only')
                ->ordered()
                ->paginate(config('preference.threads_per_page'))
                ->appends($request->only('page'));
            });
        }
        return view('users.show', compact('user','info','intro','threads'))->with(['show_user_tab'=>'box','user_title'=>'问题箱']);
    }

    public function statuses($id, Request $request)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::intro($id):null;

        $queryid = 'UserStatus.'
        .url('/')
        .$id
        .(is_numeric($request->page)? 'P'.$request->page:'P1');

        $statuses = Cache::remember($queryid, 10, function () use($request, $id) {
            return DB::table('statuses')
            ->join('users','users.id','=','statuses.user_id')
            ->leftjoin('titles','titles.id','=','users.title_id')
            ->orderBy('statuses.created_at','desc')
            ->where('users.id','=',$id)
            ->select('statuses.*','users.name as user_name','titles.name as title_name','users.title_id')
            ->paginate(config('preference.statuses_per_page'))
            ->appends($request->only('page'));
        });

        return view('users.show_status', compact('user','info','intro','statuses'))->with(['show_user_tab'=>'status']);
    }

    public function comments($id, Request $request)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::intro($id):null;

        if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
            // 如果是本人，显示属于自己的所有帖
            $posts = DB::table('posts')
            ->join('threads','threads.id','=','posts.thread_id')
            ->join('users','users.id','=','posts.user_id')
            ->where('posts.deleted_at','=',null)
            ->where('threads.deleted_at','=',null)
            ->where('posts.user_id','=',$id)
            ->orderBy('posts.created_at','desc')
            ->select('posts.id','users.name','posts.is_anonymous','posts.majia','posts.brief','threads.title','posts.created_at')
            ->paginate(config('preference.posts_per_page'));
        }else{
            $queryid = 'UserComment.'
            .url('/')
            .$id
            .(is_numeric($request->page)? 'P'.$request->page:'P1');

            $posts = Cache::remember($queryid, 10, function () use($request, $id) {
                return DB::table('posts')
                ->join('threads','threads.id','=','posts.thread_id')
                ->join('users','users.id','=','posts.user_id')
                ->where('posts.deleted_at','=',null)
                ->where('threads.deleted_at','=',null)
                ->whereIn('threads.channel_id',ConstantObjects::public_channels())
                ->where('posts.is_anonymous','=',0)
                ->where('posts.user_id','=',$id)
                ->orderBy('posts.created_at','desc')
                ->select('posts.id','users.name','posts.is_anonymous','posts.majia','posts.brief','threads.title','posts.created_at')
                ->paginate(config('preference.posts_per_page'))
                ->appends($request->only('page'));
            });
        }

        return view('users.show_comment', compact('user','info','intro', 'posts'))->with(['show_user_tab'=>'comment']);
    }

}
