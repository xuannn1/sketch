<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follower;
use App\Http\Resources\UserBriefResource;
use App\Http\Resources\UserFollowResource;
use App\Http\Resources\PaginateResource;
use Validator;

use DB;

class FollowerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['follower','following']);
    }

    /**
    * follow 关注某人
    */
    public function store(User $user)
    {
        if (auth('api')->id()===$user->id){abort(403);}

        if (auth('api')->user()->isFollowing($user->id)){abort(412);}

        auth('api')->user()->follow($user->id);

        return response()->success([
            'user' => new UserBriefResource($user),
        ]);

    }

    /**
    * unfollow
    */
    public function destroy(User $user)
    {
        if (auth('api')->id()===$user->id){abort(403);}

        if (!auth('api')->user()->isFollowing($user->id)){abort(412);}

        auth('api')->user()->unfollow($user->id);

        return response()->success([
            'user' => new UserBriefResource($user),
        ]);

    }

    /**
    * switch whether to receive updates of this user
    */
    public function update(User $user, Request $request)
    {
        $relationship = auth('api')->user()->followStatus($user->id);
        if(!$relationship){abort(412);}

        $validator = Validator::make($request->all(), [
            'keep_updated' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->error($validator->errors(), 422);
        }

        auth('api')->user()->followings()->updateExistingPivot($user->id, ['keep_updated'=>$request->keep_updated]);

        $relationship = auth('api')->user()->followStatus($user->id);

        return response()->success(new UserFollowResource($relationship));
    }

    /**
    * show the profile of the relationship for the given following
    **/
    //
    public function show(User $user)
    {
        $relationship = auth('api')->user()->followStatus($user->id);

        if(!$relationship){abort(404);}

        return response()->success(new UserFollowResource($relationship));

    }

    /**
    * 好友关系
    **/
    public function follower(User $user)
    {
        $followers = $user->followers()->paginate(config('constants.index_per_page'));
        return response()->success([
            'user'=> new UserBriefResource($user),
            'followers' => UserBriefResource::collection($followers),
            'paginate' => new PaginateResource($followers),
        ]);
    }

    public function following(User $user)
    {
        $followings = $user->followings()->paginate(config('constants.index_per_page'));

        return response()->success([
            'user'=> new UserBriefResource($user),
            'followings' => UserBriefResource::collection($followings),
            'paginate' => new PaginateResource($followings),
        ]);
    }

    public function followingStatuses(User $user)
    {
        if(auth('api')->id()!=$user->id){abort(403);}

        $followings = $user->followings()->paginate(config('constants.index_per_page'));

        return response()->success([
            'user'=> new UserBriefResource($user),
            'followingStatuses' => UserFollowResource::collection($followings),
            'paginate' => new PaginateResource($followings),
        ]);
    }

}
