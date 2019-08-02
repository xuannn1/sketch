<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvitationToken;
use Illuminate\Support\Facades\Validator;
use Carbon;
use Auth;
use CacheUser;


class InvitationTokensController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except('my_token','store_my_token');
        $this->middleware('auth')->only('my_token','store_my_token');
    }

    public function index()
    {
        $invitation_tokens = InvitationToken::where('is_public',1)
        ->orderBy('created_at', 'desc')
        ->paginate(config('constants.index_per_page'));
        return view('admin.invitation_token_index', compact('invitation_tokens'));
    }

    public function create()
    {
        return view('admin.invitation_token_create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string|alpha_dash|max:50|unique:invitation_tokens',
            'eligible-days' => 'required|numeric',
            'eligible-hours' => 'required|numeric',
            'invitation_times' => 'required|numeric',
            'refresh_times' => 'required|numeric',
        ]);

        $new_token = [];
        $new_token['token'] = $request->token;
        $new_token['invite_until'] = Carbon::now()->addDays(request('eligible-days'))->addHours(request('eligible-hours'))->toDateTimeString();
        $new_token['invitation_times'] = $request->invitation_times;
        $new_token['refresh_times'] = $request->refresh_times;
        $new_token['is_public'] = 1;
        $new_token['user_id'] = Auth::id();
        InvitationToken::create($new_token);
        return redirect()->route('invitation_tokens.index');
    }

    public function my_token()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $invitation_tokens = $user->invitation_tokens->where('is_public',0);
        $users = \App\Models\User::with('info')
        ->join('user_infos','user_infos.user_id','=','users.id')
        ->where('user_infos.invitor_id',$user->id)
        ->select('users.*')
        ->paginate(config('preferences.users_per_page'));
        return view('invitation_tokens.my_token',compact('user','users','invitation_tokens','info'));
    }

    public function store_my_token()
    {
        $info = CacheUser::Ainfo();
        if($info->token_limit<=0){abort(403);}
        $new_token = [];
        $new_token['token'] = 'SOSAD_InviteBy'.Auth::id().'_'.str_random(10);
        $new_token['invite_until'] = Carbon::now()->addDays(10)->toDateTimeString();
        $new_token['invitation_times'] = 1;
        $new_token['user_id'] = Auth::id();
        $new_token['is_public'] = false;
        $new_token['token_level'] = 2;
        InvitationToken::create($new_token);

        $info->update(['token_limit'=>$info->token_limit-1]);
        return redirect()->route('invitation_token.my_token');
    }
}
