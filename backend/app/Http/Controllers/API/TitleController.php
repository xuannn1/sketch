<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\TitleResource;

class TitleController extends Controller
{
    public function __construct()
    {

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
            $titles = $user->titles;
        }else{
            $titles = $user->publicTitles;
        }
        return response()->success([
            'titles' => TitleResource::collection($titles),
        ]);

    }


    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Title  $title
    * @return \Illuminate\Http\Response
    */
    public function update(User $user, Title $title)
    {
        // if(auth('api')->check()&&(auth('api')->id()===$user->id||auth('api')->user()->isAdmin())){
        //     $titles = $user->titles;
        // }
        //用户可以控制，是否隐藏某个title为不公开，是否佩戴该title
    }

}
