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
      $channel = ConstantObjects::allChannels()->keyBy('id')->get($thread->channel_id);
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
          'body' => 'string|max:20000',
          'preview' => 'string|max:50',
      ];
    }


    public function updatePost($post)
    {
        $channel = ConstantObjects::allChannels()->keyBy('id')->get($thread->channel_id);
        $post_data = $this->only('body','preview');
        $post_data['use_markdown']=$this->use_markdown ? true:false;
        $post_data['use_indentation']=$this->use_indentation ? true:false;
        $post_data['allow_as_longpost']=$this->allow_as_longpost ? true:false;
        if (($this->is_anonymous)&&($channel->allow_anonymous)){
            $post_data['is_anonymous']=true;
        }else{
            $post_data['is_anonymous']=false;
        }
        $post_data['last_edited_at']=Carbon::now();

        $post->update($post_data);
        return $post;
    }

}
