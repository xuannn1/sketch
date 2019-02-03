<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Thread;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
use App\Http\Resources\PostResource;

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
        //需要增加关于 最新评论，最高评论部分
        return response()->success([
            'post' =>  new PostResource($post),
        ]);
        //考虑一下，应该怎么返回对post的评论？如果它又是一个component，怎么处理
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
    public function update(Thread $thread, StorePost $form, $id)
    {
        $form->updatePost($id);
        return response()->success(new PostResource($post));

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
