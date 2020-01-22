<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Http\Resources\UserBriefResource;
use App\Http\Resources\PaginateResource;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateProfile($user,Request $request)
    {
        if(!auth('api')->user()->isAdmin()&&($user!=auth('api')->id())){abort(403);}
        $this->validate($request, [
            'body' => 'required|string|max:45'
        ]);
        $result=UserProfile::updateOrCreate(['user_id'=>$user],['body'=>request('body')] );
            if ($result) {
                return response()->success('200');
            } else {
                return response()->error(config('error.595'), 595);
            }
    }

    public function received($id, Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $info->clear_column('reward_reminders');
        $rewards = Reward::with('rewardable','author')
        ->where('receiver_id',$user->id)
        ->orderBy('created_at','desc')
        ->paginate(config('preference.rewards_per_page'));
        return view('rewards.index_received', compact('user', 'info', 'rewards'))->with('show_reward_tab','received');

    }

    public function sent($id, Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $rewards = Reward::with('rewardable','author')
        ->where('user_id',$user->id)
        ->orderBy('created_at','desc')
        ->paginate(config('preference.rewards_per_page'));
        return view('rewards.index_sent', compact('user', 'info', 'rewards'))->with('show_reward_tab','sent');
    }

}
