<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sosadfun\Traits\AdministrationTraits;
use Auth;
use Hash;
use App\Models\User;
use App\Models\Status;
use Carbon;
use App\Models\HistoricalEmailModification;
use App\Models\PasswordReset;
use CacheUser;
use Cache;
use ConstantObjects;
use App\Sosadfun\Traits\SwitchableMailerTraits;
use App\Sosadfun\Traits\CollectionObjectTraits;

class UsersController extends Controller
{

    use AdministrationTraits;
    use SwitchableMailerTraits;
    use CollectionObjectTraits;

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
        $record = PasswordReset::where('email','=',$user->email)->latest()->first();
        $last_email_sent = $record? $record->created_at:'';
        $email_confirmed = $info->activation_token ? false:true;
        return view('users.edit', compact('user', 'info','last_email_sent','email_confirmed'));
    }

    public function edit_email()
    {
        $user = Auth::user();
        $previous_history_counts = HistoricalEmailModification::where('user_id','=',Auth::id())->where('created_at','>',Carbon::now()->subMonth(1)->toDateTimeString())->count();
        return view('users.edit_email', compact('user','previous_history_counts'));
    }

    public function update_email(Request $request)
    {
        $user = Auth::user();
        $info = $user->info;
        if(Hash::check(request('old-password'), $user->password)) {
            $this->validate($request, [
                'email' => 'required|string|email|max:255|unique:users|confirmed',
            ]);
            $old_email = $user->email;

            if($old_email==$request->email){
                return redirect()->back()->with('warning','已经修改为这个邮箱，无需重复修改。');
            }

            $previous_history_counts = HistoricalEmailModification::where('user_id','=',Auth::id())->where('created_at','>',Carbon::now()->subMonth(1)->toDateTimeString())->count();
            if ($previous_history_counts>=config('constants.monthly_email_resets')){
                return redirect()->back()->with('warning','一个月内只能修改'.config('constants.monthly_email_resets').'次邮箱。');
            }
            $record = HistoricalEmailModification::create([
                'old_email' => $old_email,
                'new_email' => request('email'),
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'old_email_verified_at' => $info->email_verified_at,
                'token' => str_random(30),
            ]);

            $this->sendChangeEmailRecordTo($user, $record);

            $user->email = $request->email;
            $info->activation_token = str_random(30);
            $user->activated = 0;
            $user->save();
            $info->save();
            return redirect()->route('user.edit', Auth::id())->with("success", "您已成功修改个人资料");
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
            return redirect()->route('user.edit', Auth::id())->with("success", "您已成功修改个人密码");
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
        $intro = $user->intro;

        $info->update([
            'brief_intro'=>$request->brief_intro,
            'has_intro'=>1,
        ]);
        if($intro){
            $intro->update([
                'body'=>$request->introduction,
                'edited_at' => Carbon::now(),
            ]);
            CacheUser::clear_intro($user->id);
        }else{
            \App\Models\UserIntro::create([
                'user_id' => $user->id,
                'body'=>$request->introduction,
                'edited_at' => Carbon::now(),
            ]);
        }
        return redirect()->route('user.show', $user->id);
    }

    public function edit_preference()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        if(!$user||!$info){abort(404);}
        $groups = $this->findCollectionGroups($user->id);
        $lists = \App\Models\Thread::withUser($user->id)->WithType('list')->select('id','title')->get();
        return view('users.edit_preference', compact('user','info','groups','lists'));
    }

    public function update_preference(Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        if(!$user||!$info){abort(404);}

        $data = [];
        $data['no_upvote_reminders'] = $request->no_upvote_reminders? true:false;
        $data['no_reward_reminders'] = $request->no_reward_reminders? true:false;
        $data['no_message_reminders'] = $request->no_message_reminders? true:false;
        $data['no_reply_reminders'] = $request->no_reply_reminders? true:false;
        $data['no_stranger_msg'] = $request->no_stranger_msg? true:false;

        if($request->default_list_id){
            $list_id = (int)$request->default_list_id;
            $list_ids = \App\Models\Thread::withUser($user->id)->WithType('list')->select('id','title')->get()->pluck('id')->toArray();
            if(in_array($list_id, $list_ids)){
                $data['default_list_id']=$list_id;
            }
        }

        if($request->default_collection_group_id){
            $group_id = (int)$request->default_collection_group_id;
            $group_ids = $this->findCollectionGroups($user->id)->pluck('id')->toArray();
            if(in_array($group_id, $group_ids)){
                $data['default_collection_group_id']=$group_id;
            }
        }
        $info->update($data);
        return redirect()->route('user.center', Auth::id())->with("success", "您已成功修改偏好设置");
    }

    public function qiandao()
    {
        $user = Auth::user();
        $info = $user->info;
        if($user->qiandao_at > Carbon::today()->subHours(2)->toDateTimeString()){
            return back()->with("info", "您已领取奖励，请勿重复签到");
        }
        $message = $user->qiandao();
        return back()->with("success", $message);
    }

    public function followings($id)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$info||!$user){abort(404);}
        $intro = $info->has_intro? CacheUser::Aintro():null;
        $users = $user->followings()->paginate(config('preference.users_per_page'));
        $users->load('info','title');

        return view('users.show_follow', compact('user', 'info', 'intro', 'users'))->with(['follow_title'=>'关注的人']);
    }

    public function followers($id)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$info||!$user){abort(404);}
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
        if(!$user||!$info){abort(404);}
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

        if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()===$id))){
            $statuses = Status::with('author.title')
            ->withUser($id)
            ->ordered()
            ->paginate(config('preference.statuses_per_page'));
        }else{
            $queryid = 'UserStatus.'
            .url('/')
            .$id
            .(is_numeric($request->page)? 'P'.$request->page:'P1');

            $statuses = Cache::remember($queryid, 10, function () use($request, $id) {
                return Status::with('author.title')
                ->withUser($id)
                ->isPublic()
                ->ordered()
                ->paginate(config('preference.statuses_per_page'))
                ->appends($request->only('page'));
            });

        }

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

    protected function sendChangeEmailRecordTo($user, $record)
    {
        $view = 'auth.change_email';
        $data = compact('user', 'record');
        $to = $user->email;
        $subject = $user->name."的废文网邮箱更改提醒！";

        $this->send_email_to_select_server($view, $data, $to, $subject);
    }

}
