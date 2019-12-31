<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Review;
use App\Http\Requests\StoreThread;
use App\Http\Requests\UpdateThread;
use App\Http\Resources\ThreadInfoResource;
use App\Http\Resources\ThreadProfileResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\PaginateResource;

class ThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('filter_thread')->only('show');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $threads = Thread::threadInfo()
        ->inChannel($request->channels)
        ->isPublic()//复杂的筛选
        ->with('author', 'tags', 'last_component', 'last_post')
        ->withType($request->withType)
        ->withBianyuan($request->withBianyuan)
        ->withTag($request->tags)
        ->excludeTag($request->excludeTags)
        ->ordered($request->ordered)
        ->paginate(config('constants.threads_per_page'));
        return response()->success([
            'threads' => ThreadInfoResource::collection($threads),
            'paginate' => new PaginateResource($threads),
        ]);
        //return view('test',compact('threads'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreThread $form)//
    {
        $channel = $form->channel();
        if(empty($channel)||((!$channel->is_public)&&(!auth('api')->user()->canSeeChannel($channel->id)))){abort(403);}

        //针对创建清单进行一个数值的限制
        if($channel->type==='list'){
            $list_count = Thread::where('user_id', auth('api')->id())->withType('list')->count();
            if($list_count > auth('api')->user()->user_level){abort(403);}
        }
        if($channel->type==='box'){
            $box_count = Thread::where('user_id', auth('api')->id())->withType('box')->count();
            if($box_count >=1){abort(403);}//暂时每个人只能建立一个问题箱
        }
        $thread = $form->generateThread();
        return response()->success(new ThreadProfileResource($thread));
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $thread
    * @return \Illuminate\Http\Response
    */
    public function show(Thread $thread, Request $request)
    {
        if($request->page>1){
            $threadprofile = new ThreadBriefResource($thread);
        }else{
            $thread->load('tags', 'author', 'last_post', 'last_component');
            $threadprofile = new ThreadProfileResource($thread);
        }
        $posts = Post::where('thread_id',$thread->id)
        ->with('author', 'tags')
        ->ordered($request->ordered)//排序方式
        ->paginate(config('constants.posts_per_page'));

        $channel = $thread->channel();
        if($channel->type==='book'){
            $posts->load('chapter');
        }
        if($channel->type==='review'){
            $posts->load('review.reviewee');
            $posts->review->reviewee->load('tags','author');
        }

        return response()->success([
            'thread' => $threadprofile,
            'posts' => PostResource::collection($posts),
            'paginate' => new PaginateResource($posts),
        ]);

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(StoreThread $form, Thread $thread)
    {
        $thread = $form->updateThread($thread);
        return response()->success(new ThreadProfileResource($thread));

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

    public function synctags(Request $request, Thread $thread)
    {
        if(auth('api')->id()!=$thread->user_id){
            abort(403);
        }
        $original_tags = json_decode($request->tags);
        $validated_tags = $thread->tags_validate($original_tags);
        if($original_tags===$validated_tags){
            $thread->remove_custom_tags();
            $thread->tags()->syncWithoutDetaching($validated_tags);
            return response()->success(['tags' => $validated_tags]);
        }else{
            return response()->error([
                'original_tags' => $original_tags,
                'validated_tags' => $validated_tags,
            ], 422);
        }
    }
}
