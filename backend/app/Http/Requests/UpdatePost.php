<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Thread;
use App\Helpers\StringProcess;
use App\Helpers\ConstantObjects;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use DB;


class UpdatePost extends FormRequest
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
      $post = request()->route('post');
      return ((auth('api')->id() === $post->user_id)&&(!$thread->locked)&&($channel->allow_edit));

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
          'body' => '|string|max:20000',
          'title' => 'string|max:50',
          'preview' => 'string|max:50',
          'is_anonymous' => 'boolean',
          'use_markdown' => 'boolean',
          'use_indentation' => 'boolean',
          'allow_as_longpost' => 'boolean',
      ];
    }


    public function updatePost($post)
    {
        $channel = $thread->channel();
        $post_data = $this->only('body', 'title', 'preview', 'is_anonymous', 'use_markdown', 'use_indentation', 'allow_as_longpost');
        if (!$channel->allow_anonymous){$post_data['is_anonymous']=false;}
        $post_data['last_edited_at']=Carbon::now();

        $post->update($post_data);
        return $post;
    }

}
