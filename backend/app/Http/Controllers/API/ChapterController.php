<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Thread;
use App\Models\Post;

use App\Http\Requests\StoreChapter;
use App\Http\Resources\PostResource;


class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('filter_thread');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Thread $thread, StoreChapter $form)
    {
        $post = $form->generateChapter();
        return response()->success(new PostResource($post));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Thread $thread, StoreChapter $form, $id)
    {
        $post = Post::find($id);
        $post = $form->updateChapter($post);
        return response()->success(new PostResource($post));
    }

}
