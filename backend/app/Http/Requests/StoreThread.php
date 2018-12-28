<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Models\Thread;
use App\Helpers\StringProcess;
use App\Helpers\ConstantObjects;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
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
        $channel = ConstantObjects::allChannels()->keyBy('id')->get($this->channel);
        return (auth('api')->check())&&(!empty($channel))&&(auth('api')->user()->user_group > $channel->channel_state);
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
            'channel' => 'required|numeric',
            'majia' => 'string|max:10',
        ];
    }

    public function generateThread()
    {
        $channel = ConstantObjects::allChannels()->keyBy('id')->get($this->channel);
        //检查tag是否符合规则
        //这部分还没做
        $thread = $this->only('title','brief','body');
        //处理标题
        $thread['title'] = StringProcess::convert_to_public($thread['title']);
        //假如经过去敏感词，标题竟然为空，返回违禁信息
        if (empty($thread['title'])){
            abort(488);
        }
        //处理简介、正文，正文自动去除段首空格
        $thread['brief'] = StringProcess::convert_to_public($thread['brief']);
        $thread['body'] = StringProcess::trimSpaces($thread['body']);
        //增加其他的变量
        $thread['creation_ip'] = request()->getClientIp();
        $thread['channel_id']=$channel->id;
        //将boolean值赋予提交的设置

        if (($this->is_anonymous)&&($channel->allow_anonymous)){
            $thread['is_anonymous']=1;
            $thread['majia']=$this->majia;
            auth('api')->user()->update(['majia'=>$this->recent_majia]);
        }else{
            $thread['is_anonymous']=0;
        }
        $thread['no_reply']=$this->no_reply ? true:false;
        $thread['use_markdown']=$this->use_markdown ? true:false;
        $thread['use_indentation']=$this->use_indentation ? true:false;
        $thread['is_bianyuan']=$this->is_bianyuan ? true:false;
        $thread['last_responded_at']=Carbon::now();
        $thread['user_id'] = auth('api')->id();

        if (!$this->isDuplicateThread($thread)){
            $thread = DB::transaction(function () use($thread) {
                $thread = Thread::create($thread);
                //如果是homework，注册相关信息
                //这里还需要记录奖励历史信息
                return $thread;
            });
        }else{
            abort(409);
        }
        return $thread;
    }

    public function isDuplicateThread($thread)
    {
        $last_thread = Thread::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_thread)) && (strcmp($last_thread->title.$last_thread->brief.$last_thread->body, $thread['title'].$thread['brief'].$thread['body']) === 0);
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
