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
        $this->middleware('auth:api')->except('index','show');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO 注销
    }

    public function getInfo($user,Request $request)
    {
        // TODO 获取用户的个人偏好等信息
    }

    public function updateInfo($user,Request $request)
    {
        // TODO 修改用户的个人偏好等信息
    }

    public function updateIntro($user, Request $request)
    {
        if(!auth('api')->user()->isAdmin()&&($user!=auth('api')->id())){abort(403);}

        $user = CacheUser::user($user);

        $this->validate($request, [
            'body' => 'required|string|max:2000'
        ]);

        $result=UserIntro::updateOrCreate([
            'user_id'=>$user
        ],[
            'body' => request('body'),
            'edited_at' => Carbon::now(),
        ] );
        return $result;
    }

    public function showThread($user,Request $request)
    {
        // TODO 修改用户的个人偏好等信息
    }

    public function showPost($user,Request $request)
    {
        // TODO 修改用户的个人偏好等信息
    }

    public function showStatus($user,Request $request)
    {
        // TODO 修改用户的个人偏好等信息
    }

}
