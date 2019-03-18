<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follower;
use App\Http\Resources\UserBriefResource;
use App\Http\Resources\FollowerResource;
use App\Http\Resources\PaginateResource;

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
    * switch whether to receive notifications
    */
    // TODO： 需要补test
    public function update(User $user, Request $request)
    {
        $validatedData = $request->validate([
            'keep_updated' => 'required|boolean',
        ]);
        auth('api')->user()->followings()->updateExistingPivot($user->id, ['keep_updated'=>$request->keep_updated]);

        $relationship = auth('api')->user()->followings()->where('id', $user->id)->first();

        return response()->success(new FollowerResource($relationship));
    }

    /**
    * show the profile of the relationship for the given following
    **/
    // TODO： 需要补test
    public function show(User $user)
    {
        $relationship = auth('api')->user()->followings()->where('id', $user->id)->first();

        if(!$relationship){abort(404);}

        return response()->success(new FollowerResource($relationship));

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

}
