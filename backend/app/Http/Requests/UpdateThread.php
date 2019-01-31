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
        $channel = $thread->channel();
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
            'title' => 'string|max:30',
            'brief' => 'string|max:50',
            'body' => 'string|max:20000',
            'is_anonymous' => 'boolean',
            'no_reply' => 'boolean',
            'use_indentation' => 'boolean',
            'is_bianyuan' => 'boolean',
            'is_public' => 'boolean',
        ];

    }

    public function updateThread($thread)
    {
        //create thread data
        $channel = $thread->channel();
        $thread_data = $this->only('title', 'brief', 'body', 'is_anonymous', 'no_reply', 'use_markdown', 'use_indentation', 'is_bianyuan', 'is_public');
        if (!$channel->allow_anonymous){
            $thread_data['is_anonymous']=false;
        }
        $thread_data['last_edited_at']=Carbon::now();

        //还需要搞
        $thread = DB::transaction(function () use($thread, $thread_data) {
            $thread->update($thread_data);
            return $thread;
        });

        return $thread;

    }


}
