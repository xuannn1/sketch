<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Thread;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
use App\Http\Resources\PostResource;
use App\Http\Resources\ThreadProfileResource;
use App\Http\Resources\ThreadBriefResource;
use App\Http\Resources\PaginateResource;

class PostController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('filter_thread');

    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Thread $thread, Request $request)
    {
        $posts = Post::where('thread_id',$thread->id)
        ->with('author','tags')
        ->withType($request->withType)//可以筛选显示比如只看post，只看comment，只看。。。
        ->withComponent($request->withComponent)//可以选择是只看component，还是不看component
        ->userOnly($request->userOnly)//可以只看某用户（这样选的时候，默认必须同时属于非匿名）
        ->withReplyTo($request->withReplyTo)//可以只看用于回复某个回帖的
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
            'thread' => new ThreadBriefResource($thread),
            'posts' => PostResource::collection($posts),
            'paginate' => new PaginateResource($posts),
        ]);

        //return view('test', compact('posts'));
        //上面这一行代码，是为了通过debugler测试query实际效率。
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Thread $thread, StorePost $form)
    {

        $post = $form->generatePost();
        return response()->success(new PostResource($post));
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show(Thread $thread,Post $post)
    {
        if($thread->id!=$post->thread_id){abort(403);}
        return response()->success(new PostResource($post));
    }


    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Thread $thread, StorePost $form, Post $post)
    {
        $form->updatePost($post);
        return response()->success(new PostResource($post));

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Thread $thread, Post $post)
    {
        if($post->user_id===auth('api')->id()){
            if($post->type==='post'||$post->type==='comment'){
                $post->delete();
            }else{

            }
        }
    }

    public function turnToPost(Thread $thread, Post $post)
    {
        $channel = $thread->channel();
        if($post->thread_id===$thread->id&&auth('api')->id()===$thread->user_id){
            if($post->type==='chapter'){
                $chapter = $post->chapter;
                if($chapter){
                    $chapter->delete();
                    $post->update([
                        'type' => 'post',
                        'edited_at' => Carbon::now(),
                    ]);
                }
            }
            if($post->type==='review'){
                $review = $post->review;
                if($review){
                    $review->delete();
                    $post->update([
                        'type' => 'post',
                        'edited_at' => Carbon::now(),
                    ]);
                }
            }
            if($post->type==='question'||$post->type==='answer'){
                $post->update([
                    'type' => 'post',
                    'edited_at' => Carbon::now(),
                ]);
            }
            return response()->success(new PostResource($post));
        }
        return response()->error(config('error.403'), 403);
    }
}
