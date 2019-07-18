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
            'majia' => 'string|max:10',
            'title' => 'string|nullable|max:25',
        ];
    }

    public function storePost($thread)
    {
        $data = $this->generatePostData($thread);
        $data = $this->addReplyData($data);
        $post = Post::create($data);
        return $post;
    }

    public function updatePost(Post $post)
    {
        $data = $this->only('body','title');
        $data['body'] = StringProcess::trimSpaces($data['body']);
        $data['char_count'] = iconv_strlen($data['body'], 'utf-8');
        $data['brief']=StringProcess::trimtext($data['body'], 45);
        $data['is_anonymous']=$this->is_anonymous&&$post->thread->channel()->allow_anonymous ? 1:0;
        $data['use_markdown']=$this->use_markdown ? true:false;
        $data['use_indentation']=$this->use_indentation ? true:false;
        $data['edited_at']=Carbon::now();
        $post->update($data);
        return $post;
    }
}
