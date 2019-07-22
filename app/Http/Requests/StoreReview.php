<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;
use StringProcess;
use App\Models\Thread;
use App\Models\Review;
use App\Models\Post;
use Carbon;
use App\Sosadfun\Traits\GeneratePostDataTraits;

class StoreReview extends FormRequest
{
    use GeneratePostDataTraits;
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return true;
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'title' => 'nullable|string|max:30',
            'brief' => 'nullable|string|max:50',
            'body' => 'required|string|min:15|max:20000',
            'rating' => 'numeric|min:0|max:10',
            'thread_id' => 'numeric|min:0',
        ];
    }

    public function generateReview($thread){
        $post_data = $this->generatePostData($thread);
        $post_data['type'] = 'review';
        $post_data['brief'] = $this->brief;
        $post_data['is_anonymous']=$thread->is_anonymous;
        $post_data['majia']=$thread->majia;
        if($this->is_bianyuan){
            $post_data['is_bianyuan']=true;
        }

        // chapter data
        $review_data = $this->only('rating','thread_id');
        $review_data['recommend'] = $this->recommend? true:false;

        $post = DB::transaction(function()use($post_data, $review_data){
            $post = Post::create($post_data);
            $review_data['post_id']=$post->id;
            $review = Review::create($review_data);
            return $post;
        });
        return $post;
    }

    public function updateReview($post, $thread)
    {
        $review = $post->review;
        $post_data = $this->generateUpdatePostData($post);
        $post_data['brief'] = $this->brief;
        $post_data['is_anonymous']=$thread->is_anonymous;
        if($this->is_bianyuan){
            $post_data['is_bianyuan']=true;
        }
        $review_data = $this->only('rating','thread_id');
        $review_data['recommend'] = $this->recommend? true:false;

        $post->update($post_data);
        $review->update($review_data);

        return $post;

    }
}
