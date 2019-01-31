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
        $channel = ConstantObjects::allChannels()->keyBy('id')->get(request()->channel);

        return (auth('api')->check())&&(!empty($channel))&&(($channel->is_public)||(auth('api')->user()->canSeeChannel($channel->id)));
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return arr
    */
    public function rules()
    {
        return [
            'title' => 'required|string|max:30',
            'brief' => 'required|string|max:50',
            'body' => 'required|string|max:20000',
            'channel' => 'required|numeric',
            'majia' => 'string|max:10',
        ];
    }

    public function generateThread()
    {
        $channel = ConstantObjects::allChannels()->keyBy('id')->get($this->channel);
        //检查tag是否符合规则

        //这部分还没做
        $thread_data = $this->only('title','brief','body');
        //增加其他的变量
        $thread_data['creation_ip'] = request()->getClientIp();
        $thread_data['channel_id']=$channel->id;
        //将boolean值赋予提交的设置

        if (($this->is_anonymous)&&($channel->allow_anonymous)){
            $thread_data['is_anonymous']=1;
            $thread_data['majia']=$this->majia;
        }else{
            $thread_data['is_anonymous']=0;
        }
        $thread_data['no_reply']=$this->no_reply ? true:false;
        $thread_data['use_markdown']=$this->use_markdown ? true:false;
        $thread_data['use_indentation']=$this->use_indentation ? true:false;
        $thread_data['is_bianyuan']=$this->is_bianyuan ? true:false;
        $thread_data['is_public']=$this->is_not_public ? false:true;
        $thread_data['last_responded_at']=Carbon::now();
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


}
