<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ConstantObjects;
use DB;

class UpdateThread extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      $thread = request()->route('thread');
      $channel = $thread->channel;
      return ((auth('api')->user()->canManageChannel($thread->channel_id))||((auth('api')->id() === $thread->user_id)&&(!$thread->locked)&&($channel->allow_edit)));

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
          'title' => 'required|string|max:30',
          'brief' => 'required|string|max:50',
          'body' => 'required|string|min:10|max:20000',
          'majia' => 'string|max:10',
      ];
    }

    public function updateThread($thread)
    {
        $tags = explode(',',$this->tags);
        $tags = $thread->tags_validate($tags,$this->is_bianyuan);
        $thread['is_bianyuan']=$this->is_bianyuan ? true:false;
        $thread['title'] = $this->title;
        //todo 此处只检查了tags，对title，body等其他字段没做处理
        $thread = DB::transaction(function () use($thread,$tags) {
            $thread->save();
            $thread->tags()->sync($tags);
            return $thread;
            });

        return $thread;

    }


}
