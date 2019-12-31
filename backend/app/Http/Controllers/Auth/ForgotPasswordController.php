<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use DB;
use Validator;
use Carbon\Carbon;
use Cache;
use Illuminate\Database\Eloquent\Builder; 
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {

        $data = [
            'email'   => $request->email
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {
            return response()->error($data, 422);
        }

        $user_check = User::where('email', $request->email)->first();
        $data = [
            'email'   => $request->email
        ];
//该邮箱账户不存在
        if (!$user_check) {        
            return response()->error($data, 404);
        }
//当日注册的用户不能重置密码
        if ($user_check->created_at>Carbon::now()->subDay()){     
            return response()->error($data, 403);
        }

        $email_check = DB::table('password_resets')->where('email', $request->email)->first();
//该邮箱12小时内已发送过重置邮件。请不要重复发送邮件，避免被识别为垃圾邮件。 这个应该可以放到redis里面
        if ($email_check&&$email_check->created_at>Carbon::now()->subHours(12)){
            abort(403);
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

   //     Cache::put('reset-password-limit-' . request()->ip(), true, 60);

        return $response == Password::RESET_LINK_SENT
        ? response()->success(($data))
        : response()->error(config('error.595'), 595);
    }
}
