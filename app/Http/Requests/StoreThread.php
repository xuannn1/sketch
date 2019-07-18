<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Thread;
use Carbon;
use DB;
use StringProcess;


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
            'tag' => 'numeric',
        ];
    }
    public function generateThread($channel)
    {
        $thread_data = $this->only('title','brief','body');
        $thread_data['body'] = StringProcess::trimSpaces($post_data['body']);
        $thread_data['creation_ip'] = request()->getClientIp();
        $thread_data['channel_id']=$channel->id;
        $thread_data['is_anonymous']=0;
        $thread_data['no_reply']=$this->no_reply ? true:false;
        $thread_data['use_markdown']=$this->use_markdown ? true:false;
        $thread_data['use_indentation']=$this->use_indentation ? true:false;
        $thread_data['responded_at']=Carbon::now();
        $thread_data['user_id'] = auth()->id();

        // 将boolean值赋予提交的设置
        if ($this->is_anonymous&&$channel->allow_anonymous){
            $thread_data['is_anonymous']=1;
            $thread_data['majia']=$this->majia;
            auth()->user()->update(['majia'=>$this->majia]);
        }
        while(StringProcess::convert_to_public($thread_data['title'])!=$thread_data['title']){
           $thread_data['title'] = StringProcess::convert_to_public($thread_data['title']);
        }
        while(StringProcess::convert_to_public($thread_data['brief'])!=$thread_data['brief']){
           $thread_data['brief'] = StringProcess::convert_to_public($thread_data['brief']);
        }
        if(!$thread_data['title']||!$thread_data['brief']){
            abort('409','标题简介违规');
        }

        if ($this->isDuplicateThread($thread_data)){
            abort(409,'请求已登记，请勿重复提交相同数据');
        }
        $thread = Thread::create($thread_data);
        return $thread;
    }

    public function isDuplicateThread($thread_data)
    {
        $last_thread = Thread::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->first();
        return  !empty($last_thread) && strcmp($last_thread->title.$last_thread->brief, $thread_data['title'].$thread_data['brief']) === 0;
    }

    public function updateThread(Thread $thread)
    {
        $thread_data = $this->only('title','brief','body');
        $thread_data['body'] = StringProcess::trimSpaces($post_data['body']);
        $thread_data['is_anonymous']=0;
        $thread_data['no_reply']=$this->no_reply ? true:false;
        $thread_data['use_markdown']=$this->use_markdown ? true:false;
        $thread_data['use_indentation']=$this->use_indentation ? true:false;

        // 将boolean值赋予提交的设置
        if ($this->is_anonymous&&$channel->allow_anonymous){
            $thread_data['is_anonymous']=1;
        }
        while(StringProcess::convert_to_public($thread_data['title'])!=$thread_data['title']){
           $thread_data['title'] = StringProcess::convert_to_public($thread_data['title']);
        }
        while(StringProcess::convert_to_public($thread_data['brief'])!=$thread_data['brief']){
           $thread_data['brief'] = StringProcess::convert_to_public($thread_data['brief']);
        }
        if(!$thread_data['title']||!$thread_data['brief']){
            abort('409','标题简介违规');
        }
        $thread->update($thread_data);
        return $thread;
    }
}
