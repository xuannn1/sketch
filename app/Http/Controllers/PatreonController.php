<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatreonController extends Controller
{
    $this->middleware('auth')->only('index');

    public function index()
    {
        // $user = CacheUser::Auser();
        // $info = CacheUser::Ainfo();
        // $invitation_tokens = $user->invitation_tokens->where('is_public',0);
        // $users = \App\Models\User::with('info')
        // ->join('user_infos','user_infos.user_id','=','users.id')
        // ->where('user_infos.invitor_id',$user->id)
        // ->select('users.*')
        // ->paginate(config('preferences.users_per_page'));
        // return view('invitation_tokens.my_token',compact('user','users','invitation_tokens','info'));
    }
}
