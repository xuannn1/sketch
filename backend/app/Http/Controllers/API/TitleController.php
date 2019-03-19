<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\TitleResource;
use App\Http\Resources\UserBriefResource;
use App\Http\Resources\PaginateResource;
use Validator;

class TitleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(User $user)
    {
        if(auth('api')->check()
        && (auth('api')->id()===$user->id
        || auth('api')->user()->isAdmin())){
            $titles = $user->titles()->paginate(config('constants.index_per_page'));
        }else{
            $titles = $user->publicTitles()->paginate(config('constants.index_per_page'));
        }

        return response()->success([
            'user'=> new UserBriefResource($user->load('mainTitle')),
            'titles' => TitleResource::collection($titles),
            'paginate' => new PaginateResource($titles),
        ]);

    }


    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Title  $title
    * @return \Illuminate\Http\Response
    */
    public function update(User $user, Title $title, Request $request)
    {
        if(auth('api')->id()!=$user->id
            &&!auth('api')->user()->isAdmin()){
            abort(403);
        }

        $titleStatus = auth('api')->user()->titleStatus($title->id);
        if(!$titleStatus){abort(412);}

        switch ($request->option) {
            case 'wear'://佩戴头衔
            $user->wearTitle($title->id);
            $user->titles()->updateExistingPivot($title->id, ['is_public'=>true]);
            break;
            case 'public'://公开头衔
            $user->titles()->updateExistingPivot($title->id, ['is_public'=>true]);
            break;
            case 'hide'://隐藏头衔
            $user->titles()->updateExistingPivot($title->id, ['is_public'=>false]);
            break;
            default://默认按时间顺序排列，最早的在前面
            abort(422,'option is not allowed');
        }

        $user->load('mainTitle');
        $titleStatus = auth('api')->user()->titleStatus($title->id);

        return response()->success([
            'user' => new UserBriefResource($user),
            'title' => new TitleResource($titleStatus),
        ]);
    }

}
