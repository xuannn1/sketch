<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreReview;

use App\Models\Post;
use App\Models\Thread;

use App\Http\Resources\PostResource;
use App\Http\Resources\PaginateResource;

class ReviewController extends Controller
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
        $reviews = Post::join('reviews', 'posts.id', '=', 'reviews.post_id')
        ->reviewThread($request->thread_id)
        ->reviewRecommend($request->withRecommend ?? 'recommend_only')
        ->reviewEditor($request->withEditor)
        ->reviewLong($request->withLong)
        ->reviewMaxRating($request->withMaxRating)
        ->reviewMinRating($request->withMinRating)
        ->reviewOrdered($request->ordered)
        ->select('posts.*')
        ->paginate(config('constants.posts_per_page'));
        $reviews->load('review.reviewee','author','tags');
        return response()->success([
            'reviews' => PostResource::collection($reviews),
            'paginate' => new PaginateResource($reviews),
        ]);
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
        $post->load('review.reviewee');
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
        $post = Post::find($id);
        $post = $form->updatereview($post);
        $post->load('review.reviewee');
        return response()->success(new PostResource($post));
    }
}
