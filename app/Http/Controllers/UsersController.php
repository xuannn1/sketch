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
use App\Sosadfun\Traits\UserObjectTraits;
use App\Sosadfun\Traits\ListObjectTraits;
use App\Sosadfun\Traits\BoxObjectTraits;

class UsersController extends Controller
{

    use AdministrationTraits;
    use SwitchableMailerTraits;
    use CollectionObjectTraits;
    use UserObjectTraits;
    use ListObjectTraits;
    use BoxObjectTraits;

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['followers','followings','index','show','threads','lists','statuses','update_email_by_token'],
        ]);
    }

    public function edit()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $record = PasswordReset::where('email','=',$user->email)->latest()->first();
        $last_email_sent = $record? $record->created_at:'';
        return view('users.edit', compact('user', 'info', 'last_email_sent'));
    }

    public function edit_email()
    {
        $user = Auth::user();
        if(Cache::has('email-modification-limit-' . request()->ip())){
            return redirect('/')->with('danger', '你的IP今天已经修改过邮箱，请不要重复修改邮箱');
        }
        $previous_history_counts = HistoricalEmailModification::where('user_id','=',Auth::id())->where('created_at','>',Carbon::now()->subMonth(1)->toDateTimeString())->count();
        if ($previous_history_counts>=3){
            return redirect()->back()->with('warning','一月内只能修改3次邮箱。');
        }
        return view('users.edit_email', compact('user','previous_history_counts'));
    }

    public function recover_email()
    {

    }

    public function update_email(Request $request)
    {
        $user = Auth::user();
        $info = $user->info;
        if(Cache::has('email-modification-limit-' . request()->ip())){
            return redirect('/')->with('danger', '你的IP今天已经修改过邮箱，请不要重复修改邮箱');
        }
        if(!Hash::check(request('old-password'), $user->password)) {
            return back()->with("danger", "你的旧密码输入错误");
        }
        $this->validate($request, [
            'email' => 'required|string|email|max:255|unique:users|confirmed',
        ]);
        $old_email = $user->email;

        if($old_email==$request->email){
            return redirect()->back()->with('warning','已经修改为这个邮箱，无需重复修改。');
        }

        $previous_history_counts = HistoricalEmailModification::where('user_id','=',Auth::id())->where('created_at','>',Carbon::now()->subMonth(1)->toDateTimeString())->count();
        if ($previous_history_counts>=3){
            return redirect()->back()->with('warning','一月内只能修改3次邮箱。');
        }

        $record = HistoricalEmailModification::create([
            'old_email' => $old_email,
            'new_email' => request('email'),
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'old_email_verified_at' => $info->email_verified_at,
            'token' => str_random(30),
            'email_changed_at' => $user->activated? null : Carbon::now(),
        ]);

        // return view('auth.change_email_confirmed',compact('user','record'));

        Cache::put('email-modification-limit-' . request()->ip(), true, 1440);

        if($info->email_verified_at){
            $this->sendChangeEmailRecordTo($user, $record, true);
            return redirect()->route('user.edit', Auth::id())->with("success", "重置邮箱请求已登记，请查收已验证邮箱，根据指示完成重置操作");
        }else{
            $this->sendChangeEmailRecordTo($user, $record, false);

            $user->forceFill([
                'email' => $request->email,
                'remember_token' => str_random(60),
                'activated' => 0,
            ])->save();

            $info->forceFill([
                'activation_token' => str_random(30),
            ])->save();
            return redirect()->route('user.edit', Auth::id())->with("success", "邮箱已重置");
        }
    }

    public function update_email_by_token($token){
        $record = HistoricalEmailModification::where('token',$token)->first();
        if(!$record){
            return redirect()->back()->with('warning','输入的token已失效或不存在');
        }
        $user = CacheUser::user($record->user_id);
        $info = CacheUser::info($record->user_id);
        if($record->new_email==$user->email){
            return redirect()->back()->with('warning','已经转化成本邮箱，无需继续重置');
        }
        if($record->old_email!=$user->email){
            return redirect()->back()->with('warning','原邮箱已更改，信息失效无法再行修改');
        }
        if(!$user||!$info){
            abort(404);
        }
        $user->forceFill([
            'email' => $record->new_email,
            'remember_token' => str_random(60),
            'activated' => 0,
        ])->save();

        $info->forceFill([
            'activation_token' => str_random(30),
        ])->save();

        $record->update([
            'token'=>null,
            'email_changed_at' => Carbon::now(),
        ]);

        session()->flash('success', '邮箱已重置');

        return redirect('/');

    }

    public function edit_password(){
        $user = Auth::user();
        if(!$user->activated){
            return redirect()->back()->with('danger','你未激活邮箱，为保护账户安全，暂不能重置密码。');
        }
        return view('users.edit_password', compact('user'));
    }

    public function update_password(Request $request){
        $user = Auth::user();
        if(!$user->activated){
            return redirect()->back()->with('danger','你未激活邮箱，为保护账户安全，暂不能重置密码。');
        }
        if(Hash::check(request('old-password'), $user->password)) {
            $this->validate($request, [
                'password' => 'required|min:10|max:32|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{6,}$/',
            ]);

            $user->forceFill([
                'password' => bcrypt(request('password')),
                'remember_token' => str_random(60),
            ])->save();

            return redirect()->route('user.edit', Auth::id())->with("success", "你已成功修改个人密码");
        }
        return back()->with("danger", "你的旧密码输入错误");
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
        return redirect()->route('user.center');route('user.center', Auth::id());
    }

    public function edit_preference()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        if(!$user||!$info){abort(404);}
        $groups = $this->findCollectionGroups($user->id);
        $lists = $this->findLists($user->id);
        $boxes = $this->findBoxes($user->id);
        return view('users.edit_preference', compact('user','info','groups','lists','boxes'));
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
            $list_ids = $this->findLists($user->id)->pluck('id')->toArray();
            if(in_array($list_id, $list_ids)){
                $data['default_list_id']=$list_id;
            }
        }

        if($request->default_box_id){
            $box_id = (int)$request->default_box_id;
            $box_ids = $this->findBoxes($user->id)->pluck('id')->toArray();
            if(in_array($box_id, $box_ids)){
                $data['default_box_id']=$box_id;
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
        return redirect()->route('user.center')->with("success", "你已成功修改偏好设置");
    }

    public function qiandao()
    {
        $user = Auth::user();
        $info = $user->info;
        if($user->qiandao_at > Carbon::today()->subHours(2)->toDateTimeString()){
            return back()->with("info", "你已领取奖励，请勿重复签到");
        }
        $message = $user->qiandao();
        return back()->with("success", $message);
    }

    public function complement_qiandao()
    {
        $user = Auth::user();
        $info = $user->info;
        if($info->qiandao_reward_limit <=0){
            return back()->with("warning", "你的补签额度不足");
        }
        if($info->qiandao_continued >$info->qiandao_last){
            return back()->with("info", "你的连续签到天数超过了上次断签天数，无需补签");
        }

        $info->complement_qiandao();
        return back()->with("success", '成功补签');
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
            $threads = \App\Models\Thread::with('tags','author', 'last_component')
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
                return \App\Models\Thread::with('tags','author','last_component')
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

        return view('users.show_book', compact('user','info','intro','threads'))->with(['show_user_tab'=>'book', 'user_title'=>'书籍']);
    }

    public function threads($id, Request $request)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::intro($id):null;

        if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
            $threads = \App\Models\Thread::with('tags','author','last_post')
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
                return \App\Models\Thread::with('tags','author','last_post')
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
            $threads = \App\Models\Thread::with('tags','author','last_post')
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
                return \App\Models\Thread::with('tags','author','last_post')
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
            $threads = \App\Models\Thread::with('tags','author','last_post')
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
                return \App\Models\Thread::with('tags','author','last_post')
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

        if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
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

        return view('users.show_status', compact('user','info','intro','statuses'))->with(['show_user_tab'=>'status'])->with('status_expand',true)->with('status_show_title',false);
    }

    public function comments($id, Request $request)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);
        if(!$user||!$info){abort(404);}
        $intro = $info->has_intro? CacheUser::intro($id):null;

        if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
            $posts = $this->select_user_comments(1, 1, $id,$request);
        }elseif(Auth::check()&&Auth::user()->level>0){
            $posts = $this->select_user_comments(0, 1, $id,$request);
        }else{
            $posts = $this->select_user_comments(0, 0, $id,$request);
        }

        return view('users.show_comment', compact('user','info','intro', 'posts'))->with(['show_user_tab'=>'comment']);
    }

    protected function sendChangeEmailRecordTo($user, $record, $confirmed=false)
    {
        $view = 'auth.change_email_not_confirmed';
        if($confirmed){
            $view = 'auth.change_email_confirmed';
        }
        $data = compact('user', 'record');
        $to = $user->email;
        $subject = $user->name."的废文网邮箱更改提醒！";

        $this->send_email_to_select_server($view, $data, $to, $subject);
    }

}
