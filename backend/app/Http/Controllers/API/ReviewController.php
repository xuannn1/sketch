<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreReview;

use App\Models\Post;
use App\Models\Thread;

use App\Http\Resources\PostResource;

class ReviewController extends Controller
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
    public function store(Thread $thread, StoreReview $form)
    {
        $post = $form->generateReview();
        return response()->success(new PostResource($post));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Review  $review
    * @return \Illuminate\Http\Response
    */
    public function update(Thread $thread, StoreReview $form, $id)
    {
        $post = $form->updatereview($id);
        return response()->success(new PostResource($post));
    }
}
