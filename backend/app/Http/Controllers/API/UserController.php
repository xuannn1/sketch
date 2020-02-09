<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserBriefResource;
use App\Http\Resources\PaginateResource;
use App\Sosadfun\Traits\UserObjectTraits;

class UserController extends Controller
{
    use UserObjectTraits;

    public function __construct()
    {
        $this->middleware('auth:api')->except('index','show','showThread','showPost','showStatus');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO 展示全站用户列表
        // if($request->page&&!Auth::check()){
        //     return redirect('login');
        // }
        // $queryid = 'UserIndex.'
        // .url('/')
        // .(is_numeric($request->page)? 'P'.$request->page:'P1');
        //
        // $users = Cache::remember($queryid, 10, function () use($request) {
        //     return User::with('title','info')
        //     ->orderBy('qiandao_at','desc')
        //     ->paginate(config('preference.users_per_page'))
        //     ->appends($request->only('page'));
        // });
        //
        // return view('statuses.user_index', compact('users'))->with(['status_tab'=>'user']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // TODO 展示用户个人主页,比如等级、虚拟物等信息
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
        //
        // $user = CacheUser::Auser();
        // $info = CacheUser::Ainfo();
        // if(!$user||!$info){abort(404);}
        //
        // $data = [];
        // $data['no_upvote_reminders'] = $request->no_upvote_reminders? true:false;
        // $data['no_reward_reminders'] = $request->no_reward_reminders? true:false;
        // $data['no_message_reminders'] = $request->no_message_reminders? true:false;
        // $data['no_reply_reminders'] = $request->no_reply_reminders? true:false;
        // $data['no_stranger_msg'] = $request->no_stranger_msg? true:false;
        //
        // if($request->default_list_id){
        //     $list_id = (int)$request->default_list_id;
        //     $list_ids = $this->findLists($user->id)->pluck('id')->toArray();
        //     if(in_array($list_id, $list_ids)){
        //         $data['default_list_id']=$list_id;
        //     }
        // }
        //
        // if($request->default_box_id){
        //     $box_id = (int)$request->default_box_id;
        //     $box_ids = $this->findBoxes($user->id)->pluck('id')->toArray();
        //     if(in_array($box_id, $box_ids)){
        //         $data['default_box_id']=$box_id;
        //     }
        // }
        //
        // if($request->default_collection_group_id){
        //     $group_id = (int)$request->default_collection_group_id;
        //     $group_ids = $this->findCollectionGroups($user->id)->pluck('id')->toArray();
        //     if(in_array($group_id, $group_ids)){
        //         $data['default_collection_group_id']=$group_id;
        //     }
        // }
        // $info->update($data);
        // return redirect()->route('user.center', Auth::id())->with("success", "你已成功修改偏好设置");
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
        // TODO 展示用户创建的主题

        // $user = CacheUser::user($id);
        // $info = CacheUser::info($id);
        // if(!$user||!$info){abort(404);}
        // $intro = $info->has_intro? CacheUser::intro($id):null;
        //
        // if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
        //     $threads = \App\Models\Thread::with('tags','author','last_post')
        //     ->withUser($id)
        //     ->withoutType('book')
        //     ->ordered()
        //     ->paginate(config('preference.threads_per_page'));
        // }else{
        //     $queryid = 'UserThread.'
        //     .url('/')
        //     .$id
        //     .(is_numeric($request->page)? 'P'.$request->page:'P1');
        //
        //     $threads = Cache::remember($queryid, 10, function () use($request, $id) {
        //         return \App\Models\Thread::with('tags','author','last_post')
        //         ->withUser($id)
        //         ->withoutType('book')
        //         ->isPublic()
        //         ->inPublicChannel()
        //         ->withAnonymous('none_anonymous_only')
        //         ->ordered()
        //         ->paginate(config('preference.threads_per_page'))
        //         ->appends($request->only('page'));
        //     });
        // }
        // return view('users.show', compact('user','info','intro','threads'))->with(['show_user_tab'=>'thread','user_title'=>'主题']);
    }

    public function showPost($user,Request $request)
    {
        // TODO 展示用户创建的回帖

        // $user = CacheUser::user($id);
        // $info = CacheUser::info($id);
        // if(!$user||!$info){abort(404);}
        // $intro = $info->has_intro? CacheUser::intro($id):null;
        //
        // if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
        //     $posts = $this->select_user_comments(1, 1, $id,$request);
        // }elseif(Auth::check()&&Auth::user()->level>0){
        //     $posts = $this->select_user_comments(0, 1, $id,$request);
        // }else{
        //     $posts = $this->select_user_comments(0, 0, $id,$request);
        // }
        //
        // return view('users.show_comment', compact('user','info','intro', 'posts'))->with(['show_user_tab'=>'comment']);
    }

    public function showStatus($user,Request $request)
    {
        // TODO 展示用户创建的动态

        // $user = CacheUser::user($id);
        // $info = CacheUser::info($id);
        // if(!$user||!$info){abort(404);}
        // $intro = $info->has_intro? CacheUser::intro($id):null;
        //
        // if(Auth::check()&&((Auth::user()->isAdmin())||(Auth::id()==$id))){
        //     $statuses = Status::with('author.title')
        //     ->withUser($id)
        //     ->ordered()
        //     ->paginate(config('preference.statuses_per_page'));
        // }else{
        //     $queryid = 'UserStatus.'
        //     .url('/')
        //     .$id
        //     .(is_numeric($request->page)? 'P'.$request->page:'P1');
        //
        //     $statuses = Cache::remember($queryid, 10, function () use($request, $id) {
        //         return Status::with('author.title')
        //         ->withUser($id)
        //         ->isPublic()
        //         ->ordered()
        //         ->paginate(config('preference.statuses_per_page'))
        //         ->appends($request->only('page'));
        //     });
        //
        // }
        //
        // return view('users.show_status', compact('user','info','intro','statuses'))->with(['show_user_tab'=>'status'])->with('status_expand',true)->with('status_show_title',false);
    }
    

}
