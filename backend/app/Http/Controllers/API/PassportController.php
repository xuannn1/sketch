<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Hash;
use Carbon;
use Cache;
use ConstantObjects;
use DB;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\PasswordReset;
use App\Models\HistoricalPasswordReset;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Sosadfun\Traits\SwitchableMailerTraits;

class PassportController extends Controller
{
    use SwitchableMailerTraits;

    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }

    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|alpha_dash|unique:users|display_length:2,8',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:10|max:32|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{6,}$/',
        ]);
        //password_confirmation must be included in this string
    }

    /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return \App\Models\User
    */

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $info = UserInfo::create([
            'user_id' => $user->id,
        ]);
        return $user;
    }

    protected function create_by_invitation_token(array $data, $invitation_token, $application)
    {
        $new_user_base = array_key_exists($invitation_token->token_level, config('constants.new_user_base')) ? config('constants.new_user_base')[$invitation_token->token_level]:'';

        return DB::transaction( function() use($data, $invitation_token, $new_user_base, $application){
            $user = User::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => bcrypt($data['password']),
                'activated' => false,
                'level' => $new_user_base? $new_user_base['level']:0,
            ]);
            $info = UserInfo::create([
                'user_id' => $user->id,
                'invitation_token' => $invitation_token->token,
                'activation_token' => str_random(45),
                'invitor_id' => $invitation_token->is_public?0:$invitation_token->user_id,
                'salt' => $new_user_base? $new_user_base['salt']:0,
                'fish' => $new_user_base? $new_user_base['fish']:0,
                'ham' => $new_user_base? $new_user_base['ham']:0,
                'creation_ip' => request()->ip(),
            ]);

            if($application){
                $application->update(['user_id'=>$user->id]);
            }

            $invitation_token->inactive_once();
            return $user;
        });
    }

    protected function create_by_invitation_email(array $data, $application)
    {
        return DB::transaction( function() use($data, $application){
            $user = User::firstOrCreate([
                'email' => $data['email']
            ],[
                'name' => $data['name'],
                'password' => bcrypt($data['password']),
                'activated' => true,
                'level' => 0,
            ]);
            $info = UserInfo::firstOrCreate([
                'user_id' => $user->id
            ],[
                'email_verified_at' => Carbon::now(),
                'creation_ip' => request()->ip(),
            ]);

            $application->update(['user_id'=>$user->id]);
            return $user;
        });
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->error($validator->errors(), 422);
        }
        $user = $this->create($request->all());
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
        $success['id'] = $user->id;
        return response()->success($success);
    }

    public function register_by_invitation(Request $request)
    {
        $user = [];

        if(ConstantObjects::black_list_emails()->where('email',request('email'))->first()){
            abort(499);
        }

        if($requset->invitation_type==='token'){

            $invitation_token = App\Models\InvitationToken::where('token', request('invitation_token'))->first();

            $application = App\Models\RegistrationApplication::where('email', request('email'))->first();

            if(!$invitation_token){abort(404);}

            if(($invitation_token->invitation_times < 1)||($invitation_token->invite_until <  Carbon::now())){abort(444);}

            $this->validator($request->all())->validate();

            $user = $this->create_by_invitation_token($request->all(), $invitation_token, $application);

        }
        if($requset->invitation_type==='email'){

            $application = RegistrationApplication::where('email',request('email'))->where('token',request('token'))->first();

            if(!$application){abort(404);}

            if($application->user_id>0){abort(409);}

            if(!$application->is_passed){abort(444);}

            $this->validator($request->all())->validate();

            $user = $this->create_by_invitation_email($request->all(), $application);
        }

        if(!$user||!$request->invitation_type){abort(422);}

        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
        $success['id'] = $user->id;
        return response()->success($success);
    }



    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            if(!$user){abort(404);}
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['name'] =  $user->name;
            $success['id'] = $user->id;
            return response()->success($success);
        }
        else{
            return response()->error(config('error.401'), 401);
        }
    }

    public function logout(){
        // TODO: deactivate current token
    }
    protected function reset(array $data) // TODO:这里需要修改
    {
        // TODO: 这里需要使用forcefill，否则password不会改变。见Eloquent fillable说明。
        // TODO：不应该使用updateorcreate，修改密码的这种情况下如果找不到就创建user，这不符合逻辑...需要从上一步传递User和UserInfo模型，如果不能找到，进行报错。
        // TODO: email verified field 转移到UserInfo里，需要找到并修改。
        // TODO: 如果是未激活的用户，通过邮箱重置密码则自动激活
        // TODO: 为防盗号卖号，注册第一天的用户不允许重置密码
        // TODO: 为了便于未来核查账户安全，完成重置密码之后，需要在HistoricalPasswordReset模型里留下对应的记录，记录中需包括旧密码的值
    }
    public function reset_password_via_email(Request $request)
    {
        $data = $request->all();
        $rules = [
            'password' => 'required|string|min:10|max:32|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{6,}$/',
                ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {echo $validator->errors();
            return response()->error($validator->errors()->first(), 422);
        }
        if(Cache::has($request->token)){
            $email=Cache::get($request->token);
        }
        else{
            return response()->error("token过期或不存在", 404);
        }
        
        $token_check = DB::table('password_resets')->where('email',$email)->first();
        if(!$token_check||!hash::check($request->token,$token_check->token))
            return response()->error("找不到重置请求", 404);
            //email及token的配对不存在重置表
        if ($token_check&&$token_check->created_at<Carbon::now()->subMinutes(30)){
            return response()->error("token过期",422);
          //  token过期
        }
       // $user_check = DB::table('users')->where('email',$email)->first(); 
        $user_check = USER::where('email',$email)->first(); 
        if(!$user_check)  
            return response()->error("邮箱不存在", 404);//邮箱不存在user用户表   
        // if($user_check&&$user_check->email_verified_at>Carbon::now()->subHours(12))
        //     return response()->error("12小时内已成功重置密码不能重置密码", 409);//12小时内已成功重置密码不能重置密码

        HistoricalPasswordReset::create([
            'user_id' => $user_check->id,
            'ip_address' => request()->ip(),
            'old_password' => $user_check->password,
        ]);
        $user_check->password=bcrypt($request->password);
        $user_check->remember_token=str_random(60);
        $user_check->save();
            
        if($user_check){
            $info = $user_check->info;
            $info->activation_token=null;
            $info->email_verified_at = Carbon::now();
            $info->save;
            Auth::guard()->login($user_check);
            $token_update= PASSWORDRESET::where('email',$email)->forceDelete();
            if(!$token_update)
                return response()->error('db error',595);
            return response()->success(200);
        }else
            return response()->error('db error',595);
    }

    protected function sendChangeEmailConfirmationTo($user, $record)
    {
        $view = 'auth.confirm_email_change';
        $data = compact('user', 'record');
        $to = $record->new_email;
        $subject = $user->name."的废文网账户信息更改确认！";

        $this->send_email_from_ses_server($view, $data, $to, $subject);
    }

    protected function sendChangeEmailNotificationTo($user, $record)
    {
        $view = 'auth.change_email_notification';
        $data = compact('user', 'record');
        $to = $user->email;
        $subject = $user->name."的废文网账户信息更改提醒！";

        $this->send_email_from_ses_server($view, $data, $to, $subject);
    }
}
