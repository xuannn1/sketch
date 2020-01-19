<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Thread;
use App\Models\Post;
use Carbon;
use DB;
use StringProcess;
use App\Sosadfun\Traits\GeneratePostDataTraits;

class StorePost extends FormRequest
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
            'body' => 'required|string|min:10|max:20000',
            'reply_to_id' => 'numeric',
            'majia' => 'nullable|string|max:10',
            'title' => 'string|nullable|max:30',
        ];
    }

    public function storePost($thread)
    {
        $data = $this->generatePostData($thread);
        $data = $this->addReplyData($data, $thread);
        $post = Post::create($data);
        return $post;
    }

    public function updatePost(Post $post)
    {
        $old_post = $post;
        $data = $this->generateUpdatePostData($post);
        $post->update($data);
        $this->check_length($old_post,$post);
        return $post;
    }

}
