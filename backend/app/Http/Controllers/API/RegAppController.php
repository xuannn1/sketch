<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegAppController extends Controller
{
    public function submit_email(Request $request)
    {
        //TODO: 如果是黑名单邮箱，直接拒绝
        // TODO：如果未注册过，增加新的申请记录，并且新建一套测试题，返回测试题的题目要求回答
        // TODO：如果未注册过，已答题，未验证邮箱，告诉对方需要提交邮箱确认码
        // TODO：如果已经验证邮箱，提供小论文题目
        // TODO：如果已经验证邮箱，而且已经提交申请，返回当前申请进度供查询
    }

    public function submit_quiz(Request $request)
    {
        //TODO:提交答题的结果
        //TODO：如果题目不正确，后台题目清零（避免重复刷题）
        //TODO:如果题目正确，给邮箱发送确认邮件
    }

    public function resend_email_verification(Request $request)
    {
        //TODO:重新发送确认邮件
    }

    public function submit_essay(Request $request)
    {
        //TODO:提交小论文
    }

    public function submit_email_verification(Request $request)
    {
        //TODO:提交邮件确认码
    }

    public function resend_invitation_email(Request $request)
    {
        //TODO:重新发送邀请邮件
    }

    // 以下都是过度系统的内容，供参考业务逻辑
    // public function register_by_invitation_email_submit_email(Request $request)
    // {
    //     $this->validate($request, [
    //         'email' => 'required|string|email|max:255',
    //         'g-recaptcha-response' => 'required|nocaptcha'
    //     ]);
    //
    //     if(Cache::has('IP-refresh-limit-' . request()->ip())){
    //         return redirect('/')->with('danger', '本IP（'.request()->ip().'）在5分钟内已访问过注册申请页面，请等待至少5分钟后再重新访问');
    //     }
    //     Cache::put('IP-refresh-limit-' . request()->ip(), true, 5);
    //
    //     $message = $this->checkApplicationViaEmail($request->email);
    //
    //     if(!array_key_exists('success', $message)){return back()->with($message);}
    //
    //     $application = $this->findApplicationViaEmail($request->email);
    //
    //     if(!$application){
    //
    //         if(preg_match('/qq\.com/', $request->email)){
    //             return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger', 'qq邮箱拒收本站邮件，请勿使用qq邮箱。');
    //         }
    //
    //         if(preg_match('/\.con$/', $request->email)){
    //             return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger', '请确认邮箱拼写正确。');
    //         }
    //
    //         $application = RegistrationApplication::UpdateOrCreate([
    //             'email' => $request->email,
    //         ],[
    //             'ip_address' => request()->ip(),
    //             'email_token' => str_random(10),
    //             'token' => str_random(35),
    //         ]);
    //         $this->refreshCheckApplicationViaEmail($request->email);
    //         $this->refreshFindApplicationViaEmail($request->email);
    //     }
    //
    //     if(!$application->is_passed&&!$application->cut_in_line&&!$application->has_quizzed){
    //         return redirect()->route('register.by_invitation_email.take_quiz_form',['email'=>$request->email]);
    //     }
    //
    //     if(!$application->email_verified_at&&!$application->cut_in_line&&!$application->last_invited_at){
    //         return redirect()->route('register.by_invitation_email.submit_email_verification_form',['email'=>$request->email]);
    //     }
    //
    //     if($application->email_verified_at&&!$application->is_passed&&!$application->cut_in_line&&$application->has_quizzed&&!$application->submitted_at){
    //         return redirect()->route('register.by_invitation_email.submit_essay_form',['email'=>$request->email]);
    //     }
    //
    //     return view('auth.registration.by_invitation_email.application_result', compact('application'));
    //
    // }
    //
    // public function register_by_invitation_email_submit_email_verification_form(Request $request){
    //     $application = $this->findApplicationViaEmail($request->email);
    //     if(!$application||$application->is_forbidden){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','申请档案信息有误，无法验证邮箱。请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //     if($application->email_verified_at){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('info','邮箱已验证，无需重复验证。请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //     if(!$application->email_token){
    //         $application->assign_email_token();
    //         $this->refreshFindApplicationViaEmail($request->email);
    //     }
    //     if(!$application->send_verification_at){
    //         $application->sendVerificationEmail();
    //         $this->refreshFindApplicationViaEmail($request->email);
    //     }
    //     return view('auth.registration.by_invitation_email.submit_email_verification_form', compact('application'));
    // }
    //
    // public function register_by_invitation_email_submit_email_verification(Request $request){
    //     $this->validate($request, [
    //         'email' => 'required|string|email|max:255',
    //         'email_token' => 'required|string',
    //     ]);
    //
    //     $application = $this->findApplicationViaEmail($request->email);
    //
    //     if(!$application||$application->is_forbidden){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','申请档案信息有误，无法验证邮箱。请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //
    //     if($application->email_verified_at){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('info','邮箱已验证，无需重复验证。请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //
    //     if($request->email_token!=$application->email_token){
    //         Cache::put('IP-refresh-limit-' . request()->ip(), true, 5);
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','邮箱验证码不正确，无法验证邮箱真实有效。');
    //     }
    //
    //     $application->update([
    //         'email_verified_at' => Carbon::now(),
    //         'ip_address_verify_email' => request()->ip(),
    //     ]);
    //
    //     $this->refreshFindApplicationViaEmail($request->email);
    //     if($application->submitted_at){
    //         return view('auth.registration.by_invitation_email.application_result', compact('application'));
    //     }
    //     return redirect()->route('register.by_invitation_email.submit_essay_form',['email'=>$request->email])->with('success','已成功验证邮箱');
    // }
    //
    // public function register_by_invitation_email_submit_essay_form(Request $request){
    //     $application = $this->findApplicationViaEmail($request->email);
    //     if(!$application||$application->is_forbidden||!$application->has_quizzed||!$application->email_verified_at){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('warning','你有待完成的步骤，不能回答问卷。请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //     if($application->cut_in_line||$application->is_passed||$application->last_invited_at){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('info','你已通过申请，无需重复申请。请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //     if($application->submitted_at&&$application->submitted_at > Carbon::now()->subDays(config('constants.application_cooldown_days'))){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('warning','申请排队中，请耐心等待，勿更换邮箱重复提交申请，重复提交会进入黑名单。');
    //     }
    //     if($application->essay_question_id===0||($application->submitted_at&&$application->submitted_at<Carbon::now()->subDays(config('constants.application_cooldown_days')))){
    //         $application->assign_essay_question();
    //         $this->refreshFindApplicationViaEmail($request->email);
    //     }
    //     return view('auth.registration.by_invitation_email.submit_essay_form', compact('application'));
    // }
    //
    // public function register_by_invitation_email_submit_essay(Request $request){
    //     $application = $this->findApplicationViaEmail($request->email);
    //     if(!$application||$application->cut_in_line||$application->is_passed||!$application->has_quizzed||($application->submitted_at&&$application->submitted_at > Carbon::now()->subDays(config('constants.application_cooldown_days')))){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','页面数据失效，请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //     $this->validate($request, [
    //         'application' => 'required|string|min:450|max:2000',
    //         'finished' => 'required|string',
    //     ]);
    //     if($request->finished!="已完成"){
    //         return back()->with('warning', '完成确认语不正确。');
    //     }
    //     $application->update([
    //         'body' => $request->application,
    //         'submitted_at' => Carbon::now(),
    //         'reviewer_id' => 0,
    //         'reviewed_at' => null,
    //         'submission_count' => $application->submission_count+1,
    //         'ip_address_submit_essay' => request()->ip(),
    //     ]);
    //     $this->refreshFindApplicationViaEmail($request->email);
    //     return redirect()->route('register.by_invitation_email.submit_email_form')->with('success','成功提交申请，请耐心等待审核通过。');
    // }
    //
    // public function register_by_invitation_email_take_quiz_form(Request $request){
    //     if(Cache::has('Ratelimit-email-application-quiz-form' . $request->email)){
    //         return redirect('/')->with('danger', '你在5分钟内已经尝试过答题，请稍后再答');
    //     }
    //     Cache::put('Ratelimit-email-application-quiz-form' . $request->email, true, 5);
    //
    //     $application = $this->findApplicationViaEmail($request->email);
    //     if(!$application||$application->cut_in_line||$application->is_passed||$application->has_quizzed||$application->is_forbidden){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','页面数据失效，请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //     $quizzes = $this->random_quizzes(-1, 'register', config('constants.registration_quiz_total'));
    //     $quiz_questions = implode(",", $quizzes->pluck('id')->toArray());
    //     $application ->update(['quiz_questions' => $quiz_questions]);
    //     $this->refreshFindApplicationViaEmail($request->email);
    //     return view('auth.registration.by_invitation_email.take_quiz_form', compact('application', 'quizzes'));
    // }
    //
    // public function register_by_invitation_email_take_quiz(Request $request){
    //     if(Cache::has('Ratelimit-email-application-quiz' . $request->email)){
    //         return redirect('/')->with('danger', '你在5分钟内已经尝试过答题，请稍后再答');
    //     }
    //     Cache::put('Ratelimit-email-application-quiz' . $request->email, true, 5);
    //
    //     $application = $this->findApplicationViaEmail($request->email);
    //     if(!$application||$application->cut_in_line||$application->is_passed||$application->has_quizzed||$application->submitted_at){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','页面数据失效，请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //
    //     $quiz_ids = explode(',',$application->quiz_questions);
    //     $passed_quiz_questions = 0;
    //     $passed_quiz = true;
    //     foreach($quiz_ids as $quiz_id){
    //         $quiz = $this->find_quiz_set($quiz_id);
    //         $answer = implode(',',$quiz->quiz_options->where('is_correct',true)->pluck('id')->toArray());
    //         $quiz->delay_count('quiz_count', 1);
    //
    //         if(request('quiz-answer')&&is_array(request('quiz-answer'))&&array_key_exists($quiz_id, request('quiz-answer'))&&request('quiz-answer')[$quiz_id]===$answer){
    //             $passed_quiz_questions += 1;
    //             $quiz->delay_count('correct_count', 1);
    //         }
    //     }
    //     if($passed_quiz_questions<config('constants.registration_quiz_correct')){
    //         $passed_quiz = false;//答对的题的数量小于7道的话
    //     }
    //     if($passed_quiz){
    //         $application->update([
    //             'has_quizzed'=>1,
    //             'quiz_count' => $application->quiz_count+1,
    //             'ip_address_last_quiz' => request()->ip(),
    //         ]);
    //         $application->assign_essay_question();
    //         $this->refreshFindApplicationViaEmail($request->email);
    //         return redirect()->route('register.by_invitation_email.submit_email_verification_form',['email'=>$request->email])->with('success','已成功答题');
    //     }
    //     $application->update([
    //         'quiz_count' => $application->quiz_count+1,
    //     ]);
    //
    //     $this->refreshFindApplicationViaEmail($request->email);
    //     return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','抱歉，本次答对'.$passed_quiz_questions.'题，未超过'.config('constants.registration_quiz_correct').'题，尚不能进入下一步，请5分钟后重新作答。');
    // }
    //
    // public function register_by_invitation_email_submit_token($token)
    // {
    //     $application = RegistrationApplication::where('token',$token)->first();
    //
    //     if(!$application){
    //         return redirect('/')->with('danger', "注册链接无效，请检查拼写，完整复制粘贴");
    //     }
    //     if($application->user_id>0){
    //         return redirect('/')->with('danger', "本链接已成功注册，请凭接收链接的邮箱，直接登陆自己的账户。如果忘记密码，可以凭邮箱找回密码。");
    //     }
    //
    //     if(!$application->is_passed){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','链接已失效，请重新查询申请进度。');
    //     }
    //
    //     return view('auth.registration.by_invitation_email.submit_registration', compact('application'));
    // }
    //
    // public function register_by_invitation_email_resend_email($email)
    // {
    //     $application = RegistrationApplication::where('email',$email)->first();
    //     if(!$application||!$application->is_passed||!$application->last_invited_at||$application->last_invited_at>=Carbon::now()->subDay(1)||$application->user_id>0){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','页面数据失效，请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //     $application->sendInvitationEmail();
    //     return redirect()->route('register.by_invitation_email.submit_email_form')->with('success','已成功重发邀请邮件，请及时查收');
    // }
    //
    // public function register_by_invitation_email_resend_email_verification($email)
    // {
    //     $application = RegistrationApplication::where('email',$email)->first();
    //     if(!$application||!$application->is_passed||$application->user_id>0||($application->send_verification_at&&$application->send_verification_at>=Carbon::now()->subDay(1))){
    //         return redirect()->route('register.by_invitation_email.submit_email_form')->with('danger','页面数据失效，请输入邮箱，重新查询申请状态。友情提醒，申请中请勿使用浏览器回退功能。');
    //     }
    //     $application->sendVerificationEmail();
    //     return redirect()->route('register.by_invitation_email.submit_email_form')->with('success','已成功重发邀请邮件，请及时查收');
    // }
}
