<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Chapter;
use App\Http\Requests\StoreThread;
use App\Http\Resources\ThreadResources\ThreadInfoResource;
use App\Http\Resources\ThreadResources\ThreadProfileResource;
use App\Http\Resources\ThreadResources\PostResource;
use App\Http\Resources\ThreadResources\ChapterInfoResource;
use App\Http\Resources\ThreadResources\VolumnResource;
use App\Http\Resources\PaginateResource;

class ThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show', 'showbook']);
        $this->middleware('filter_thread')->only(['show','showbook']);
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $threads = Thread::threadInfo()
        ->inChannel($request->channel)
        ->isPublic()
        ->with('author', 'tags')
        ->withBook($request->withBook)
        ->withBianyuan($request->withBianyuan)
        ->withTag($request->tag)
        ->excludeTag($request->excludeTag)
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
        $thread = $form->generateThread();
        return response()->success($thread);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $thread
    * @return \Illuminate\Http\Response
    */
    public function show($thread)
    {
        $thread = Thread::find($thread);
        $thread->load('author');
        $posts = Post::where('thread_id',$thread->id)
        ->with('author')
        ->orderBy('created_at','asc')
        ->paginate(config('constants.posts_per_page'));
        return response()->success([
            'thread' => new ThreadProfileResource($thread),
            'posts' => PostResource::collection($posts),
            'paginate' => new PaginateResource($posts),
        ]);
    }

    public function showbook($thread)
    {
        $thread = Thread::find($thread);
        if($thread){
            $thread->load('author', 'tags');
            $posts = Post::where('thread_id',$thread->id)
            ->where('is_maintext', true)
            ->with('chapter.volumn')
            ->get();
            $posts->sortBy('chapter.order_by');
            $volumns = $posts->pluck('chapter.volumn')->unique();
            return response()->success([
                'thread' => new ThreadProfileResource($thread),
                'chapters' => ChapterInfoResource::collection($posts),
                'volumns' => VolumnResource::collection($volumns),
            ]);
        }else{
            return response()->error(config('error.404'), 404);
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
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
        //
    }
}
