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
            'label' => 'required|numeric',
            'majia' => 'string|max:10',
        ];
    }

    public function generateThread()
    {
        //检查频道、大类信息是否符合规则
        $channel = ConstantObjects::allChannels()->keyBy('id')->get($this->channel);
        $label = ConstantObjects::allLabels()->keyBy('id')->get($this->label);
        //$channel->channel_state===1 意味着这是一篇文章，不应该采取普通thread的方式存储
        if(empty($channel)||empty($label)||$label->channel_id!=$channel->id||($channel->channel_state===1)){
            abort(481);
        }
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
        $thread['thread_group'] = $channel->channel_state;//边缘类的state怎么处理？？
        $thread['creation_ip'] = request()->getClientIp();
        $thread['channel_id']=$channel->id;
        $thread['label_id']=$label->id;
        //将boolean值赋予提交的设置
        //$channel->channel_state如果为3，意味着这属于投诉仲裁板块。投诉仲裁板块不能匿名开楼，不能进行修改自己的楼层
        if (($this->is_anonymous)&&($channel->channel_state!=3)){
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
                // $thread->user->reward("regular_thread");
                // if($thread->label_id == 50){
                //     $thread->registerhomework();
                // }
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
