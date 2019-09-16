<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\User;
use Carbon;
use CacheUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
    * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
        Session::put('backUrl', URL::previous());
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        //up to this point, exactly the same as in original AuthenticatesUsers package

        // Customization: Validate if client status is active (1)
        $email = $request->get($this->username());
        // Customization: It's assumed that email field should be an unique field
        $user = User::where($this->username(), $email)->first();
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        // Customization: If client status is inactive (0) return failed_status error.
        if($user){
            if ($user->no_logging == 1) {
                $info = CacheUser::info($user->id);
                $msg = $info->no_logging_until&&$info->no_logging_until>Carbon::now() ? $info->no_logging_until:'';
                abort(495,$msg);
            }
        }

        return $this->sendFailedLoginResponse($request);
    }
    /**
    * Get the needed authorization credentials from the request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    protected function credentials(Request $request)
    {
        if(filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)){
            $credentials = ['email'=>$request->get('email'),'password'=>$request->get('password')];
        }else{
            $credentials = ['name'=>$request->get('email'),'password'=>$request->get('password')];
        }
        return $credentials;
    }
    /**
    * Get the failed login response instance.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  string  $field
    * @return \Illuminate\Http\RedirectResponse
    */
    protected function sendFailedLoginResponse(Request $request, $trans = 'auth.failed')
    {
        $errors = [$this->username() => trans($trans)];
        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
        ->withInput($request->only($this->username(), 'remember'))
        ->withErrors($errors);
    }

    function authenticated(Request $request, $user)
    {
        $info = $user->info;
        $info->login_at = Carbon::now();
        $info->login_ip = $request->getClientIp();
        $info->save();
    }
    public function redirectTo()
    {
        return (Session::get('backUrl') ? Session::get('backUrl') : $this->redirectTo);
    }
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->back();
    }
    public function getLogout()
    {
        Auth::logout();

        return redirect()->back();
    }

}
