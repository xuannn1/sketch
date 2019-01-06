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
      $channel = $thread->channel; //todo
      $post = request()->route('post');
      return ((auth('api')->user()->canManageChannel($thread->channel_id))||((auth('api')->id() === $post->user_id)&&(!$thread->locked)&&($channel->allow_edit)));

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
          'body' => 'required|string|max:20000'
      ];
    }


    public function updatePost($post){

        $data = $this->only('body');
        $data['body'] = StringProcess::trimSpaces($data['body']);
        $data['preview']=StringProcess::trimtext($data['body'], 50);
        $data['is_anonymous']=$this->anonymous ? 1:0;
        $data['use_markdown']=$this->markdown ? true:false;
        $data['use_indentation']=$this->indentation ? true:false;
        $data['allow_as_longpost']=$this->as_longcomment ? true:false;
        $data['last_edited_at']=Carbon::now();

        $post->update($data);
        return $post;
    }

}
