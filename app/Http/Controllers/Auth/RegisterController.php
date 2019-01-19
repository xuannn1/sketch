<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\InvitationToken;
use Carbon\Carbon;
use Mail;

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
        $this->middleware('guest');
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'have_read_policy' => 'required',
            'invitation_token' => 'required|string|exists:invitation_tokens,token|max:255',
       ]);
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
        if (!Cache::has('registration-limit-' . request()->ip())){
            $user = DB::transaction(function()use($data){
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'invitation_token' => $data['invitation_token'],
                    'activated' => true,
                ]);
                $expiresAt = Carbon::now()->addMinutes(10);
                Cache::put('registration-limit-' . request()->ip(), true, $expiresAt);
                return $user;
            });
        }else{
            $user = null;
        }
        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        // event(new Registered($user));
        // $this->sendEmailConfirmationTo($user);
        // session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        if ($user){
            session()->flash('success', '账户已建立并激活，直接登录就可以玩耍了，快来试试吧！');
        }else {
            session()->flash('danger', '您的IP十分钟内已经成功注册，请尝试直接登陆您已注册的账户。');
        }
        return redirect('/');
    }

    protected function sendEmailConfirmationTo($user)
    {
        $view = 'auth.confirm';
        $data = compact('user');
        $from = env('MAIL_USERNAME','null');
        $name = env('MAIL_NAME','null');
        $to = $user->email;
        $subject = "感谢注册废文网！请确认你的邮箱。";

        Mail::queue($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        session()->flash('success', '恭喜你，激活成功！');
        return view('auth.login');
    }

}
