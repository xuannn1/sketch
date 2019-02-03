<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Review;
use App\Models\Thread;
use App\Models\Post;
use Carbon\Carbon;
use DB;

class StoreReview extends FormRequest
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
            'preview' => 'string|max:50',
            'body' => 'string|max:20000',
            'use_markdown' => 'boolean',
            'use_indentation' => 'boolean',
            'recommend' => 'boolean',
            'rating' => 'numeric|min:0|max:10',
        ];
    }

    public function generateReview()
    {
        //validation
        $reviewee = Thread::find($this->reviewee_id);//被推荐书籍
        $thread = request()->route('thread');//书评楼
        if((!$thread)||(!$reviewee)){abort(404);}

        //check if user has proper identity to review this thread
        $channel = $reviewee->channel();
        if((!$reviewee->is_public)||((!$channel->is_public)&&(!auth('api')->user()->canSeeChannel($reviewee->channel_id)))){abort(403);}

        //check if already reviewed
        $reviewed = Post::join('reviews', 'reviews.post_id', 'posts.id')
        ->where('posts.user_id', auth('api')->id())
        //->where('posts.thread_id', request()->route('thread')->id)
        ->where('reviews.thread_id', request()->reviewee_id)
        ->count();
        if($reviewed>0){abort(409);}

        $post_data = $this->only('title', 'body', 'preview', 'use_markdown', 'use_indentation');
        $post_data['type']='review';
        $post_data['thread_id'] = $thread->id;
        $post_data['creation_ip'] = request()->getClientIp();
        $post_data['user_id'] = auth('api')->id();

        //generate collection data
        $review_data = $this->only('recommend','rating');
        $review_data['thread_id']=$reviewee->id;

        //use transaction to update collection && post (if necessary)

        $post = DB::transaction(function () use($review_data, $post_data) {
            $post = Post::create($post_data);
            $review_data['post_id'] = $post->id;
            $review = Review::create($review_data);
            return $post;
        });
        return $post;
    }

    public function updateReview($id)
    {
        $post = Post::find($id);
        $review = $post->review;
        if((!$post)||(!$review)){ abort(404);}
        if(($post->type!='review')||($post->user_id!=auth('api')->id())){abort(403);}

        $post_data = $this->only('title', 'body', 'preview', 'use_markdown', 'use_indentation');
        $post_data['last_edited_at'] = Carbon::now();
        //generate collection data
        $review_data = $this->only('recommend','rating');

        //use transaction to update collection && post (if necessary)

        $post = DB::transaction(function () use($review_data, $post_data, $review, $post) {
            $post->update($post_data);
            $review->update($review_data);
            return $post;
        });
        return $post;
    }
}
