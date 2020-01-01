<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Hash;
use Carbon\Carbon;
use DB;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Foundation\Auth\ResetsPasswords;
class PassportController extends Controller
{

    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
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

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            if ($user->hasAccess(['can_not_login'])){
                $userTokens = $user->tokens;
                foreach($userTokens as $token) {
                    $token->revoke();
                }
                Auth::logout();
                abort(499);
            }else{
                $success['token'] =  $user->createToken('MyApp')->accessToken;
                $success['name'] =  $user->name;
                $success['id'] = $user->id;
                return response()->success($success);
            }
        }
        else{
            return response()->error(config('error.401'), 401);
        }
    }

    public function postReset(Request $request)
    {


        $password = $request->password;
        $data = $request->all();
        $rules = [
            'password'=>'required|between:6,20',
        ];
    
        $messages = [
            'required' => '密码不能为空',
            'between' => '密码必须是6~20位之间'
        ];
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            abort(422);
            //返回一次性错误
        }
       // $token=hash::make($request->token);
        $token_check = DB::table('password_resets')->where('email',$request->email)->first();
        if(!$token_check||!hash::check($request->token,$token_check->token))
            abort(404,$request->token);
            //email及token的配对不存在重置表
        if ($token_check&&$token_check->created_at<Carbon::now()->subMinutes(30)){
            abort(422,$request->token);
          //  token过期
        }
        $user_check = DB::table('users')->where('email',$request->email)->first(); 
        if(!$user_check)  
            abort(404,'users');//邮箱不存在用户表   
        if($user_check&&$user_check->email_verified_at>Carbon::now()->subHours(12))
            abort(409);//12小时内已成功重置密码不能重置密码
        $toekn_update= DB::table('password_resets')->where('email',$request->email)->update(
            ['created_at'=>Carbon::now()->subMinutes(20)]);
        if(!$toekn_update)
            abort(404,'faile');  
        $user = User::updateOrCreate(
            ['email'=>$data['email']],
            ['password' => bcrypt($data['password']), 'email_verified_at' => Carbon::now(),'remember_token' => Str::random(60)]);
        if($user){
            Auth::guard()->login($user);
            return response()->success('200');
        }
        abort(500);

    }  
}
