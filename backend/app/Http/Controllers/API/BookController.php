<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Post;
use App\Http\Resources\ThreadResources\BookInfoResource;
use App\Http\Resources\ThreadResources\ThreadProfileResource;
use App\Http\Resources\ThreadResources\ChapterInfoResource;
use App\Http\Resources\ThreadResources\VolumnResource;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\ThreadResources\PostResource;

class BookController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('filter_thread')->only('show');
    }


    public function index(Request $request)
    {
        $threads = Thread::threadInfo()
        ->inChannel($request->channel)
        ->isPublic()
        ->with('author', 'tags', 'last_chapter.chapter')
        ->withType('book')
        ->withBianyuan($request->withBianyuan ?? 'none_bianyuan_only')
        ->withTag($request->tag)
        ->excludeTag($request->excludeTag)
        ->ordered($request->ordered ?? 'last_added_component_at')
        ->paginate(config('constants.threads_per_page'));
        return response()->success([
            'threads' => BookInfoResource::collection($threads),
            'paginate' => new PaginateResource($threads),
        ]);
        //return view('test',compact('threads'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show(Thread $book)
    {
        $thread = $book;
        $thread->load('author', 'tags', 'recommendations.authors');
        $posts = Post::where('thread_id',$thread->id)
        ->where('type', '<>', 'post')
        ->with('chapter.volumn')
        ->get();
        $posts->sortBy('chapter.order_by');
        $volumns = $posts->pluck('chapter.volumn')->unique();
        $most_upvoted = Post::where('thread_id',$thread->id)
        ->where('type', '=', 'post')
        ->with('author')
        ->orderBy('up_votes', 'desc')
        ->first();
        $newest_comment = Post::where('thread_id',$thread->id)
        ->where('type', '=', 'post')
        ->with('author')
        ->orderBy('created_at', 'desc')
        ->first();
        return response()->success([
            'thread' => new ThreadProfileResource($thread),
            'chapters' => ChapterInfoResource::collection($posts),
            'volumns' => VolumnResource::collection($volumns),
            'most_upvoted' => new PostResource($most_upvoted),
            'newest_comment' => new PostResource($newest_comment),
        ]);
    }

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
