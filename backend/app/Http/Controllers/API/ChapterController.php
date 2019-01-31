<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Thread;
use App\Models\Post;
use App\Models\Chapter;

use App\Http\Requests\UpdateChapter;
use App\Http\Requests\StoreChapter;
use App\Http\Resources\ThreadResources\ChapterResource;


class ChapterController extends Controller
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

    public function index()
    {
        //
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
    public function store(Thread $thread, StoreChapter $chapterform)
    {
        $post = $chapterform -> generateChapter();
        return response()->success(new ChapterResource($post));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Thread $thread, $id)
    {
        $post = Post::find($id);
        if(($post)&&($post->type==='chapter')&&($post->chapter)){
            return response()->success(new ChapterResource($post));
        }else{
            abort(404);
        }
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
    public function update(Thread $thread, UpdateChapter $chapterform, $id)
    {
        $post = $chapterform -> updateChapter($id);
        return response()->success(new ChapterResource($post));
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
