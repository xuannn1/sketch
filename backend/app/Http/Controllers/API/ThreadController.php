<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Post;
use App\Http\Requests\StoreThread;
use App\Http\Requests\UpdateThread;
use App\Http\Resources\ThreadBriefResource;
use App\Http\Resources\ThreadInfoResource;
use App\Http\Resources\ThreadProfileResource;
use App\Http\Resources\PostIndexResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\PaginateResource;
use App\Sosadfun\Traits\ThreadQueryTraits;
use Cache;
use ConstantObjects;

class ThreadController extends Controller
{
    use ThreadQueryTraits;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show','channel_index']);
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

    public function channel_index($channel, Request $request)
    {
        if(!auth('api')->check()&&$request->page){abort(401);}

        $channel = collect(config('channel'))->keyby('id')->get($channel);

        if($channel->id===config('constants.list_channel_id')&&$request->channel_mode==='review'){
            $request_data = $this->sanitize_review_posts_request_data($request);
            $query_id = $this->process_review_posts_query_id($request_data);
            $posts = $this->find_review_posts_with_query($query_id, $request_data);
            return response()->success([
                'posts' => PostIndexResource::collection($posts),
                'paginate' => new PaginateResource($posts),
                'request_data' => $request_data,
            ]);
        }

        $primary_tags = ConstantObjects::extra_primary_tags_in_channel($channel->id);

        $queryid = 'channel-index'
        .'-ch'.$channel->id
        .'-withBianyuan'.$request->withBianyuan
        .'-withTag'.$request->withTag
        .'-ordered'.$request->ordered
        .(is_numeric($request->page)? 'P'.$request->page:'P1');

        $time = 30;
        if(!$request->withTag&&!$request->ordered&&!$request->page){$time=2;}

        $threads = Cache::remember($queryid, $time, function () use($request, $channel) {
            return $threads = Thread::with('author', 'tags', 'last_post')
            ->isPublic()
            ->inChannel($channel->id)
            ->withBianyuan($request->withBianyuan)
            ->withTag($request->withTag)
            ->ordered($request->ordered)
            ->paginate(config('preference.threads_per_page'))
            ->appends($request->only('withBianyuan', 'ordered', 'withTag','page'));
        });

        $simplethreads = $this->find_top_threads_in_channel($channel->id);

        return response()->success([
            'channel' => $channel,
            'threads' => ThreadInfoResource::collection($threads),
            'primary_tags' => $primary_tags,
            'simplethreads' => ThreadBriefResource::collection($simplethreads),
            'paginate' => new PaginateResource($threads),
        ]);

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
