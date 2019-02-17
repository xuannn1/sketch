<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Post;
use App\Http\Resources\ThreadProfileResource;
use App\Http\Resources\PostInfoResource;
use App\Http\Resources\VolumnResource;
use App\Http\Resources\VolumnBriefResource;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\PostBriefResource;

class BookController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct()
    {
        $this->middleware('filter_thread');
    }

    public function show(Thread $thread)
    {
        $thread->load('author', 'tags', 'last_component', 'last_post');
        $chapters = Post::postBrief()
        ->with('chapter')
        ->join('chapters', 'chapters.post_id','=','posts.id')
        ->where('posts.thread_id',$thread->id)
        ->where('posts.type', '=', 'chapter')
        ->orderBy('chapters.order_by', 'asc')
        ->paginate(config('constants.components_per_page'));
        $most_upvoted = $thread->most_upvoted();
        if($most_upvoted){
            $most_upvoted = new PostBriefResource($most_upvoted);
        }
        $top_review = $thread->top_review();
        if($top_review){
            $top_review->load('review');
            $top_review = new PostResource($top_review);
        }
        $volumns = $chapters->pluck('chapter.volumn')->unique();
        return response()->success([
            'thread' => new ThreadProfileResource($thread),
            'chapters' => PostInfoResource::collection($chapters),
            'paginate' => new PaginateResource($chapters),
            'volumns' => VolumnBriefResource::collection($volumns),
            'most_upvoted' => $most_upvoted,
            'top_review' => $top_review
        ]);
    }

    public function chapterindex(Thread $thread)
    {
        $thread->load('author', 'tags');
        $chapters = Post::postBrief()
        ->with('chapter')
        ->join('chapters', 'chapters.post_id','=','posts.id')
        ->where('posts.thread_id',$thread->id)
        ->where('posts.type', '=', 'chapter')
        ->orderBy('chapters.order_by', 'asc')
        ->get();

        $volumns = $chapters->pluck('chapter.volumn')->unique();
        return response()->success([
            'thread' => new ThreadProfileResource($thread),
            'chapters' => PostInfoResource::collection($chapters),
            'volumns' => VolumnResource::collection($volumns),
        ]);
    }
}
