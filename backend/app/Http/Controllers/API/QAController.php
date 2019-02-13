<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Post;
use App\Models\Thread;

use App\Http\Resources\PostResource;
use App\Http\Resources\PaginateResource;

class QAController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
        $this->middleware('filter_thread')->except('index');
    }

    public function index(Request $request)
    {
        $answers = Post::withType('answer')
        ->paginate(config('constants.posts_per_page'));
        $answers->load('parent', 'author');
        return response()->success([
            'answers' => PostResource::collection($answers),
            'paginate' => new PaginateResource($answers),
        ]);
    }

    public function turnToAnswer(Thread $thread, Post $post)
    {
        $channel = $thread->channel();
        if($channel->type==='box'&&$post->thread_id===$thread->id&&auth('api')->id()===$thread->user_id&&$post->parent){
            $parent = $post->parent;
            $parent->type = 'question';
            $parent->save();
            $post->type = 'answer';
            $post->edited_at = Carbon::now();
            $post->save();
            return response()->success(new PostResource($post));
        }
        return response()->error(config('error.403'), 403);
    }
}
