<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Carbon\Carbon;
use App\Models\Thread;
use App\Helpers\ConstantObjects;
use DB;

class StoreThread extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return true;
    }

    public function channel()
    {
        return collect(config('channel'))->keyby('id')->get($this->channel_id);
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return arr
    */
    public function rules()
    {
        return [
            'title' => 'string|max:30',
            'brief' => 'string|max:50',
            'body' => 'string|max:20000',
            'channel_id' => 'numeric',
            'majia' => 'string|max:10',
            'is_anonymous' => 'boolean',
            'no_reply' => 'boolean',
            'use_indentation' => 'boolean',
            'is_bianyuan' => 'boolean',
            'is_public' => 'boolean',
        ];
    }

    public function generateThread()
    {
        $channel = $this->channel();

        //这部分还没做
        $thread_data = $this->only('title', 'brief', 'body', 'is_anonymous', 'majia', 'no_reply', 'use_markdown', 'use_indentation', 'is_bianyuan', 'is_public');
        //增加其他的变量
        $thread_data['creation_ip'] = request()->getClientIp();
        $thread_data['channel_id']=$channel->id;
        //将boolean值赋予提交的设置
        if (!$channel->allow_anonymous){
            $thread_data['is_anonymous']=false;
        }
        $thread_data['responded_at']=Carbon::now();
        $thread_data['user_id'] = auth('api')->id();

        if (!$this->isDuplicateThread($thread_data)){
            $thread = DB::transaction(function () use($thread_data) {
                $thread = Thread::create($thread_data);
                //如果是homework，注册相关信息
                //这里还需要记录奖励历史信息
                return $thread;
            });
        }else{
            abort(409);
        }
        return $thread;
    }

    public function isDuplicateThread($thread_data)
    {
        $last_thread = Thread::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_thread)) && (strcmp($last_thread->title.$last_thread->brief, $thread_data['title'].$thread_data['brief']) === 0);
    }

    public function updateThread($thread)
    {
        //check authorization
        $channel = $thread->channel();
        if(!($channel->allow_edit||auth('api')->user()->inRole('admin'))||($thread->user_id!=auth('api')->id())){abort(403);}
        //generate $thread_data
        $thread_data = $this->only('title', 'brief', 'body', 'is_anonymous', 'no_reply', 'use_markdown', 'use_indentation', 'is_bianyuan', 'is_public');
        if (!$channel->allow_anonymous){
            $thread_data['is_anonymous']=false;
        }
        $thread_data['edited_at']=Carbon::now();

        $thread = DB::transaction(function () use($thread, $thread_data) {
            $thread->update($thread_data);
            return $thread;
        });
        return $thread;
    }
}
