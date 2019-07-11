<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Status;
use App\Models\User;
use Auth;
use CacheUser;
use App\Models\Follower;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'store', 'destroy'
        ]);
    }

    public function store($id)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);

        $Auser = CacheUser::Auser();
        $Ainfo = CacheUser::Ainfo();

        if ($Auser->id === $user->id) {
            return 'notwork';
        }

        if (!$Auser->isFollowing($id)) {
            $Auser->follow($id);

            $info->follower_count+=1;
            $info->save();
            $Ainfo->following_count+=1;
            $Ainfo->save();
        }

        return 'successfully followed user';
    }

    public function destroy($id)
    {
        $user = CacheUser::user($id);
        $info = CacheUser::info($id);

        $Auser = CacheUser::Auser();
        $Ainfo = CacheUser::Ainfo();

        if ($Auser->id === $user->id) {
            return "notwork";
        }

        if ($Auser->isFollowing($id)) {
            $Auser->unfollow($id);
            $info->follower_count-=1;
            $info->save();
            $Ainfo->following_count-=1;
            $Aiinfo->save();
        }

        return 'successfully unfollowed user';
    }
    public function togglekeepupdate(Request $request)
    {
        $follower = Follower::where([['user_id','=',request('user_id')],['follower_id','=',Auth::id()]])
        ->first();
        if ($follower){
            $follower->keep_updated = !$follower->keep_updated;
            $follower->save();
            return $follower;
        }else{
            return "notwork";
        }
    }
}
