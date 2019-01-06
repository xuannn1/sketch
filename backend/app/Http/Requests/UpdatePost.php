<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
      return ((auth('api')->user()->canManageChannel($channel))||((auth('api')->id() == $post->user_id)&&(!$thread->locked)&&($channel->allow_edit==true)));


    //  return (($thread->is_public)&&(!$thread->no_reply))||(auth('api')->id()===$thread->user_id)||(auth('api')->user()->canManageChannel($thread->channel_id));

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
          'body' => 'required|string|max:190'
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

    /**
    * Handle a failed validation attempt.
    *
    * @param  \Illuminate\Contracts\Validation\Validator  $validator
    * @return void
    *
    * @throws \Illuminate\Validation\ValidationException
    */

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->error($validator->errors(), 422));
    }
}
