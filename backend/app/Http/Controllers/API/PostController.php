<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Thread;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePost;
use App\Http\Resources\ThreadResources\PostResource;
 use Illuminate\Support\Facades\Auth;

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

    public function index($thread, Request $request)
    {

    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
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
        return response()->success($post);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show(Thread $thread,Post $post)
    {
        //return new PostResource($post);
        //应该要显示这个post，还有它的全部回帖，还有它的

        $post = Post::where('id',$post->id);
        //return view('test', compact('posts'));
        //上面这一行代码，是为了通过debugler测试query实际效率。
        return response()->success([
            'post' =>  new PostResource($post),
        ]);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(StorePost $form,  $id)
    {
        $post = Post::findorFail($id);
        $thread=$post->thread;
        $channel=$thread->channel;
        if ((Auth::user()->canManageChannel($channel)==true)||((Auth::id() == $post->user_id)&&(!$thread->locked)&&($channel->allow_edit==true))){
            $form->updatePost($post);
            return response()->success([
                'post' => new  PostResource($post),
            ]);
        }else{
            return response()->error(config('error.403'), 403);
        }

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
