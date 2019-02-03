<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Post;
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
            if($list_count > $user->user_level){abort(403);}
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
        $posts = Post::where('thread_id',$thread->id)
        ->with('author')
        ->withType($request->withType)//可以筛选显示比如只看post，只看comment，只看。。。
        ->userOnly($request->userOnly)//可以只看某用户（这样选的时候，默认必须同时属于非匿名）
        ->orderBy('created_at','asc')
        ->paginate(config('constants.posts_per_page'));

        $channel = $thread->channel();
        if($channel->type==='book'){
            $posts->load('chapter');
        }
        if($channel->type==='review'){
            $posts->load('review.reviewee');
            $posts->review->reviewee->load('tags','author');
        }

        if(!$request->page){
            //假如没有约定页码，显示thread内容
            $thread->load('author','tags','recommendations.authors');
            $thread_profile = new ThreadProfileResource($thread);
        }else{
            //不是page1的时候，不显示thread内容
            $thread_profile = [];
        }

        return response()->success([
            'thread' => $thread_profile,
            'posts' => PostResource::collection($posts),
            'paginate' => new PaginateResource($posts),
        ]);

        //return view('test', compact('posts'));
        //上面这一行代码，是为了通过debugler测试query实际效率。
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
