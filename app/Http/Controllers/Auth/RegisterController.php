<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\UserInfo;
use App\Http\Controllers\Controller;
use App\Models\InvitationToken;
use Carbon;
use Auth;
use CacheUser;
use App\Models\PasswordReset;
use App\Sosadfun\Traits\SwitchableMailerTraits;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use SwitchableMailerTraits;

    /**
    * Where to redirect users after registration.
    *
    * @var string
    */
    protected $redirectTo = '/';

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('guest')->except('resend_email_confirmation','confirmEmail');
        $this->middleware('auth')->only('resend_email_confirmation');
    }

    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|alpha_dash|max:12|unique:users',
            'email' => 'required|string|email|max:255|unique:users|confirmed',
            'password' => 'required|string|min:10|max:32|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'have_read_policy1' => 'required',
            'have_read_policy2' => 'required',
            'have_read_policy3' => 'required',
            'invitation_token' => 'required|string|exists:invitation_tokens,token|max:255',
            'promise' => 'required',
            'captcha' => 'required|captcha',
        ]);
        $validator->after(function ($validator) {
            if(request('promise')!=config('preference.register_promise')){
                $validator->errors()->add('promise', '注册担保输入不正确，请认真打字，重新输入。');
            }
        });
        return $validator;
    }

    /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return \App\Models\User
    */

    protected function create(array $data)
    {
        return DB::transaction(function()use($data){
            $invitation_token = InvitationToken::on('mysql::write')->where('token',$data['invitation_token'])->first();
            if(!$invitation_token||$invitation_token->invitation_times<=0||$invitation_token->invite_until <  Carbon::now()){
                abort(409, '注册迟了，本邀请码的邀请次数已用尽');
            }
            $invitation_token->inactive_once();
            $token_level_data = array_key_exists($invitation_token->token_level, config('constants.token_level'))? config('constants.token_level')[$invitation_token->token_level]:'';
            $user = User::firstOrCreate([
                'email' => $data['email']
            ],[
                'name' => $data['name'],
                'password' => bcrypt($data['password']),
                'activated' => false,
                'level' => $token_level_data? $token_level_data['level']:0,
            ]);
            $info = UserInfo::firstOrCreate([
                'user_id' => $user->id
            ],[
                'invitation_token' => $data['invitation_token'],
                'activation_token' => str_random(45),
                'invitor_id' => $invitation_token->is_public?0:$invitation_token->user_id,
                'salt' => $token_level_data? $token_level_data['salt']:0,
                'fish' => $token_level_data? $token_level_data['fish']:0,
                'ham' => $token_level_data? $token_level_data['ham']:0,
            ]);
            return $user;
        });
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        if(Cache::has('registration-limit-' . request()->ip())){
            return redirect('/')->with('danger', '你的IP今天已经成功注册，请尝试直接登陆你已注册的账户。');
        }
        $user = $this->create($request->all());
        Cache::put('registration-limit-' . request()->ip(), true, 1440);

        event(new Registered($user));

        return redirect('/')->with('success', '你好，你已成功注册');
    }

    protected function sendEmailConfirmationTo($user, $info)
    {
        $view = 'auth.confirm';
        $data = compact('user','info');
        $to = $user->email;
        $subject = $user->name."你好，感谢注册废文网！请确认你的邮箱。";

        $this->send_email_to_select_server($view, $data, $to, $subject);
    }

    public function confirmEmail($token)
    {
        $user_info = UserInfo::where('activation_token', $token)->first();
        if(!$user_info){return redirect('/')->with('danger','令牌已使用或已被新令牌取代');}
        $user_info->activate();

        session()->flash('success', '恭喜你，激活成功！');
        return redirect('/');
    }

    public function resend_email_confirmation(){
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        if(!$user||!$info){abort(404);}

        $email = $user->email;

        $email_check = PasswordReset::where('email','=',$user->email)->latest()->first();

        if ($email_check&&$email_check->created_at>Carbon::now()->subHours(2)){
            return back()->with('warning', '2小时内已发送过重置邮件，短时间内重复提交容易被收件公司识别为垃圾邮件，暂不再重复发送。');
        }

        DB::transaction(function()use($user, $info){
            if(!$info->activation_token){
                $info->activation_token=str_random(40);
                $info->save();
            }
            DB::table('password_resets')->where('email','=',$user->email)->delete();
            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => bcrypt($info->activation_token),
                'created_at' => Carbon::now(),
            ]);
        });

        $this->sendEmailConfirmationTo($user, $info);
        session()->flash('success', '已成功发送验证邮件，请注意查收');

        return redirect()->route('user.edit');
    }

    public function register_by_invitation_form()
    {
        return view('auth.register_by_invitation_form');
    }

    public function register_by_invitation(Request $request)
    {
        if (Cache::has('registration-by-invitation-limit-' . request()->ip())){
            return back()->with('danger','本ip('.request()->ip().')已于2分钟内尝试注册，请等待冷静期经过，请勿重复输入信息或试图暴力破解邀请码');
        }
        Cache::put('registration-by-invitation-limit-' . request()->ip(), true, 2);

        $invitation_token = InvitationToken::where('token', request('invitation_token'))->first();

        if (!$invitation_token){
            return back()->with('danger', '邀请码拼写错误，请重新检查，复制粘贴。');
        }
        if (($invitation_token->invitation_times < 1)||($invitation_token->invite_until <  Carbon::now())){
            return back()->with('danger', '邀请码已失效，请更换新版邀请码。');
        }
        $invitation_token->load('user');
        return view('auth.register_by_invitation', compact('invitation_token'));
    }

}
