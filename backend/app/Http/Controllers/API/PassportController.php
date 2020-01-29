<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Hash;
use Cache;
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
            $data = [
                "message"=> "validation failed"
           ];
            return response()->error($data, 422);
            //返回一次性错误
        }
        $err_data = [
            "token"=>$request->token 
       ];
        if(Cache::has($request->token)){
            $email=Cache::get($request->token);
        }
        else{
            return response()->error($err_data, 404);
        }
       // $token=hash::make($request->token);
        $token_check = DB::table('password_resets')->where('email',$email)->first();
        if(!$token_check||!hash::check($request->token,$token_check->token))
            return response()->error($err_data, 404);
            //email及token的配对不存在重置表
        if ($token_check&&$token_check->created_at<Carbon::now()->subMinutes(30)){
            return response()->error($err_data,422);
          //  token过期
        }
        $user_check = DB::table('users')->where('email',$email)->first(); 
        if(!$user_check)  
            return response()->error($err_data, 404);//邮箱不存在user用户表   
        if($user_check&&$user_check->email_verified_at>Carbon::now()->subHours(12))
            return response()->error($err_data, 409);//12小时内已成功重置密码不能重置密码
        $token_update= DB::table('password_resets')->where('email',$email)->update(
            ['created_at'=>Carbon::now()->subMinutes(40)]);
        if(!$token_update)
            return response()->error($err_data, 404);
        $user = User::updateOrCreate(
            ['email'=>$email],
            ['password' => bcrypt($request->password)]);
        User::updateOrCreate(
            ['email'=>$email],
            ['email_verified_at' => Carbon::now()]);
        User::updateOrCreate(
            ['email'=>$email],
            ['remember_token' => Str::random(60)]);
        
        $succ_data=[
            'token'=>$request->token
        ];    

        if($user){
            Auth::guard()->login($user);
            return response()->success($succ_data);
        }
        abort(500);

    }  
}
