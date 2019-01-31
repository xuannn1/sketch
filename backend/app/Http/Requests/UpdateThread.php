<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Helpers\ConstantObjects;
use Carbon\Carbon;
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
            'body' => 'required|string|max:20000',
            'majia' => 'string|max:10',
        ];
    }

    public function updateThread($thread)
    {
        //create thread data
        $channel = ConstantObjects::allChannels()->keyBy('id')->get($thread->channel_id);
        $thread_data = $this->only('title','brief','body');
        if (($this->is_anonymous)&&($channel->allow_anonymous)){
            $thread_data['is_anonymous']=1;
        }else{
            $thread_data['is_anonymous']=0;
        }
        $thread_data['no_reply']=$this->no_reply ? true:false;
        $thread_data['use_markdown']=$this->use_markdown ? true:false;
        $thread_data['use_indentation']=$this->use_indentation ? true:false;
        $thread_data['is_bianyuan']=$this->is_bianyuan ? true:false;
        $thread_data['is_public']=$this->is_not_public ? false:true;
        $thread_data['last_edited_at']=Carbon::now();

        //还需要搞
        $thread = DB::transaction(function () use($thread, $thread_data) {
            $thread->update($thread_data);
            return $thread;
        });

        return $thread;

    }


}
