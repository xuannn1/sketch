<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use DB;
use Carbon;

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
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $user_check = User::where('email', $request->email)->first();

        if (!$user_check) {
            return back()->with('warning', '该邮箱账户不存在。');
        }

        if ($user_check->created_at>Carbon::now()->subDay(1)){
            return back()->with('danger', '当日注册的用户不能重置密码。');
        }

        $email_check = DB::table('password_resets')->where('email', $request->email)->first();

        if ($email_check&&$email_check->created_at>Carbon::now()->subDay(1)){
            return back()->with('warning', '一天内已发送过重置邮件，请不要重复发送邮件，避免被识别为垃圾邮件。');
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
        ? $this->sendResetLinkResponse($response)
        : $this->sendResetLinkFailedResponse($request, $response);
    }
}
