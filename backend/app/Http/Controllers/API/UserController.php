<?php

namespace App\Http\Controllers\API;

use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Thread;
use App\Models\Status;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserPreferenceResource;
use App\Http\Resources\UserReminderResource;
use App\Http\Resources\ThreadInfoResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\StatusResource;
use App\Http\Resources\PaginateResource;
use App\Sosadfun\Traits\UserObjectTraits;
use App\Helpers\CacheUser;

class UserController extends Controller
{
    use UserObjectTraits;

    public function __construct()
    {
        $this->middleware('auth:api')->except('index','show','showThread','showBook','showStatus');
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
        $user_profile = [
            'user' => CacheUser::user($id),
            'info' => CacheUser::info($id),
        ];
        if (!$user_profile['user'] || !$user_profile['info']) {abort(404);}
        $user_profile['intro'] = $user_profile['info']->has_intro ? CacheUser::intro($id) : null;

        return response()->success(new UserResource($user_profile));
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

    public function getReminder($id,Request $request)
    {
        if (!auth('api')->user()->isAdmin() && auth('api')->id() != $id) {abort(403);}

        $info = CacheUser::info($id);

        return response()->success(new UserReminderResource($info));
    }

    public function updateReminder($id,Request $request)
    {
        // TODO
    }

    public function getPreference($id,Request $request)
    {
        if (!auth('api')->user()->isAdmin() && auth('api')->id() != $id) {abort(403);}

        $info = CacheUser::info($id);

        return response()->success(new UserPreferenceResource($info));
    }

    public function updatePreference($user,Request $request)
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

    public function showThread($id, Request $request)
    {
        $threads = $this->get_threads_or_books(0, $id, $request);

        return response()->success([
            'threads' => ThreadInfoResource::collection($threads),
            'paginate' => new PaginateResource($threads),
        ]);
    }

    public function showBook($id, Request $request)
    {
        $books = $this->get_threads_or_books(1, $id, $request);

        return response()->success([
            'books' => ThreadInfoResource::collection($books),
            'paginate' => new PaginateResource($books),
        ]);
    }

    public function showPost($id, Request $request)
    {
        // 管理员或本人可查看包括匿名、边缘、折叠以及发表在非公开thread、非公开板块内thread的post
        if(auth('api')->user()->isAdmin() || auth('api')->id() == $id){
            $posts = $this->select_user_comments(1, 1, $id,$request);
        }elseif(auth('api')->user()->level > 0){
            $posts = $this->select_user_comments(0, 1, $id,$request);
        } else {
            abort(403);
        }

        return response()->success([
            'posts' => PostResource::collection($posts),
            'paginate' => new PaginateResource($posts),
        ]);
    }

    public function showStatus($id,Request $request)
    {
        if(auth('api')->check() && (auth('api')->user()->isAdmin() || auth('api')->id() == $id)){
            $statuses = Status::with('author.title')
            ->withUser($id)
            ->ordered()
            ->paginate(config('preference.statuses_per_page'));
        } else {
            $queryid = 'UserStatus.'
            .$id
            .(is_numeric($request->page)? 'P'.$request->page:'P1');

            $statuses = Cache::remember($queryid, 10, function () use($request, $id) {
                return Status::with('author.title')
                ->withUser($id)
                ->isPublic()
                ->ordered()
                ->paginate(config('preference.statuses_per_page'))
                ->appends($request->only('page'));
            });

        }

        return response()->success([
            'statuses' => StatusResource::collection($statuses),
            'paginate' => new PaginateResource($statuses),
        ]);
    }

    private function get_threads_or_books($is_book, $id, $request) {

        if (auth('api')->check() && (auth('api')->user()->isAdmin() || auth('api')->id() == $id)) {
            $data = $this->select_user_threads(1, 1, 1, $is_book, $id, $request);
        } elseif (auth('api')->check() && auth('api')->user()->level > 0){
            $data = $this->select_user_threads(0, 0, 1, $is_book, $id, $request);
        } else {
            $data = $this->select_user_threads(0, 0, 0, $is_book, $id, $request);
        }

        // TODO 这个地方我想了一下，一个是不用pagination了，直接返回全部threads，因为一个人的threads数量再多也不会很多，缓存时间也可以更长一些，问题不大。
        // TODO 但是对应的，这里的threads需要有限制，briefthread格式（不需要含有文案这个很长的东西）
        // TODO 然后，这里只区分两个情况就可以了 1）本人或管理，可以看全部 2）非本人，只能看公共非匿名
        // TODO 边限那个就先不筛选了，让情况简单一点

        return $data;
    }
}
