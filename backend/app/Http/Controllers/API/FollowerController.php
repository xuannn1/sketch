<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follower;

use App\Http\Resources\FollowerResource;

class FollowerController extends Controller
{
    public function _construct()
    {
    	$this->middleware('auth:api');
    }

    /**
     * follow
     */
    public function store($id)
    {
    	//check if target user exists
    	$user = User::findOrFail($id);
    	//check !self_following && !already_following
        if (!(auth('api')->check())) return response()->error('api check not passed');
    	if (auth('api')->check() &&
                auth('api')->id() != $id && 
                !auth('api')->user()->isFollowing($id))
    	{
    		auth('api')->user()->follow($id);
    		return response()->success('followed the user');
    	}
    	return response()->error(config('error.412'), 412);
    }

    /**
     * unfollow
     */
    public function destroy($id)
    {
    	//check if target user exists
    	$user = User::findOrFail($id);
    	//check valid auth && !self_unfo && already_following
    	if (auth('api')->check() &&
                auth('api')->id()!=$id && 
                auth('api')->user()->isFollowing($id))
    	{
    		auth('api')->user()->unfollow($id);
    		return response()->success('unfollowed the user');
    	}
    	return response()->error(config('error.412'), 412);
    }

    /**
     * switch whether to receive notifications
     */
    public function toggleNotifications($id)
    {
        $query = Follower::where([['user_id','=',$id],['follower_id','=',auth('api')->id()]]);
    	$follower = $query->first();
        if ($follower){
            $tmp = !$follower->keep_notified;
            $data = ['keep_notified' => $tmp];
            $query->update($data);
            return response()->success('success');
        }
        return response()->error(config('error.412'),412);
    }

    /**
     * show the profile of the relationship for the given following
     **/
    public function show($id)
    {
        $data = Follower::where([['user_id','=',$id],['follower_id','=',auth('api')->id()]])->first();
        if ($data)
        {
            return response()->success(new FollowerResource($data));
        }
        return response()->error(config('error.412'), 412);

    }
}

