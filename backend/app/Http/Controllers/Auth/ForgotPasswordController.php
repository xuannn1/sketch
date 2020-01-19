<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Sosadfun\Traits\SwitchableMailerTraits;
use DB;
use Carbon;
use Cache;

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
    use SwitchableMailerTraits;

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {

        // TODO：把这些请求都改写成适应API的response的格式
        if(Cache::has('reset-password-request-limit-' . request()->ip())){
            return back()->with('danger','当前ip('.request()->ip().')已于10分钟内提交过重置密码请求。');
        }
        Cache::put('reset-password-request-limit-' . request()->ip(), true, 10);

        if(Cache::has('reset-password-limit-' . request()->ip())){
            return back()->with('danger','当前ip('.request()->ip().')已于1小时内成功重置密码。');
        }

        $this->validateEmail($request);

        $user = \App\Models\User::onWriteConnection()->where('email', $request->email)->first();

        if (!$user) {
            return back()->with('warning', '该邮箱账户不存在。');
        }

        if ($user->created_at>Carbon::now()->subDay(1)){
            return back()->with('danger', '当日注册的用户不能重置密码。');
        }

        $info = $user->info;

        if($info&&$info->no_logging_until&&$info->no_logging_until>Carbon::now()){
            return back()->with('danger', '封禁管理中的账户不能重置密码');
        }

        $email_check = DB::connection('mysql::write')->table('password_resets')->where('email', $request->email)->first();

        if ($email_check&&$email_check->created_at>Carbon::now()->subHours(12)){
            return back()->with('warning', '该邮箱12小时内已发送过重置邮件。请不要重复发送邮件，避免被识别为垃圾邮件。');
        }

        $token = str_random(40);

        $reset_record = \App\Models\PasswordReset::updateOrCreate([
            'email' => $request->email,
        ],[
            'token'=>bcrypt($token),
            'created_at' => Carbon::now(),
        ]);
        $this->sendEmailConfirmationTo($user, $token);

        Cache::put('reset-password-limit-' . request()->ip(), true, 60);

        return back()->with('success', '已成功发送重置密码邮件。');
    }

    protected function sendEmailConfirmationTo($user, $token)
    {
        $view = 'auth.passwords.reset_password_email';
        $data = compact('user','token');
        $to = $user->email;
        $subject = $user->name."的废文网密码重置申请";

        $this->send_email_from_ses_server($view, $data, $to, $subject);
    }
}
