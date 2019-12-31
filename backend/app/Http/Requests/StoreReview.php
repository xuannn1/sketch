<?php

namespace App\Http\Requests;

use App\Http\Requests\StorePost;
//use App\Http\Requests\FormRequest;
use App\Models\Review;
use App\Models\Thread;
use App\Models\Post;
use Carbon\Carbon;
use DB;

class StoreReview extends StorePost
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        $thread = request()->route('thread');
        $channel = $thread->channel();
        return auth('api')->id()===$thread->user_id&&$channel->type=='list'&&!$thread->is_locked;
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'reviewee_id' => 'numeric',
            'title' => 'string|max:30',
            'brief' => 'string|max:50',
            'body' => 'string|max:20000',
            'use_markdown' => 'boolean',
            'use_indentation' => 'boolean',
            'recommend' => 'boolean',
            'rating' => 'numeric|min:0|max:10',
        ];
    }

    public function generateReview()
    {
        $thread = request()->route('thread');
        $this->canReviewThread();
        $this->noDuplicateReview();

        $post_data = $this->generatePostData();
        $post_data['type'] = 'review';
        if($thread->is_anonymous){$post_data['is_anonymous']=true;}

        $review_data = $this->generateReviewData();

        //use transaction to update collection && post (if necessary)
        $post = DB::transaction(function () use($review_data, $post_data) {
            $post = Post::create($post_data);
            $review_data['post_id'] = $post->id;
            $review = Review::create($review_data);
            return $post;
        });
        return $post;
    }

    public function noDuplicateReview()
    {
        $reviewed = Post::join('reviews', 'reviews.post_id', 'posts.id')
        ->where('posts.user_id', auth('api')->id())
        //->where('posts.thread_id', request()->route('thread')->id)
        ->where('reviews.thread_id', request()->reviewee_id)
        ->count();
        if($reviewed>0){abort(409);}
    }

    public function canReviewThread()
    {
        $reviewee = Thread::find($this->reviewee_id);//被推荐书籍
        if($reviewee){
            $channel = $reviewee->channel();
            if((!$reviewee->is_public)||((!$channel->is_public)&&(!auth('api')->user()->canSeeChannel($reviewee->channel_id)))){abort(403);}
        }
    }

    public function generateReviewData()
    {
        $review_data = $this->only('recommend','rating');
        $review_data['thread_id']=$this->reviewee_id;
        $review_data['long']=(bool)mb_strlen($this->body)>config('constants.long_review');
        return $review_data;
    }

    public function updateReview($post)
    {
        $review = $post->review;
        if((!$post)||(!$review)){ abort(404);}

        $this->canUpdatePost($post);

        $post_data = $this->generateUpdatePostData();

        $review_data = $this->generateReviewData();

        $post = DB::transaction(function () use($review_data, $post_data, $review, $post) {
            $post->update($post_data);
            $review->update($review_data);
            return $post;
        });
        return $post;
    }
}
