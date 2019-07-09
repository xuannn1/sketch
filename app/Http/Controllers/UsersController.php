<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sosadfun\Traits\AdministrationTraits;
use Auth;
use Hash;
use App\Models\User;
use Carbon\Carbon;
use App\Models\EmailModifyHistory;
use App\Models\PasswordReset;
use Mail;
use CacheUser;

class UsersController extends Controller
{

    use AdministrationTraits;

    public function __construct()
    {
        $this->middleware('auth', [
            'only' => ['edit', 'update', 'edit_email', 'update_email', 'destroy', 'qiandao', 'send_email_confirmation','usercenter'],
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
                $info->rewardData(5*$reward_base, 5*$reward_base, 5*$reward_base, 1*$reward_base, 0);
                $info->message_limit = $user->level;
                $info->list_limit = $user->level;
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

    public function usercenter()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();

        return view('users.center', compact('user','info'));
    }

}
