<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Thread;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;


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
            'label' => 'required',
            'majia' => 'string|max:10',
        ];
    }
    public function generateThread($channel_id)
    {
        $thread_data = $this->only('title','brief','body');
        $post_data = [];
        $post_data['user_ip'] = $this->getClientIp();
        $thread_data['channel_id']=$channel_id;
        $thread_data['label_id']=(int)$this->label;
        //查看标签是否符合权限
        if(\App\Models\Label::find($thread_data['label_id'])->channel_id!=$channel_id){
            abort(403,'数据冲突');
        }
        //将boolean值赋予提交的设置
        if ($this->anonymous){
            $thread_data['anonymous']=1;
            $thread_data['majia']=$this->majia;
            auth()->user()->update(['majia'=>$this->majia]);
        }else{
            $thread_data['anonymous']=0;
        }
        while(Helper::convert_to_title($thread_data['title'])!=$thread_data['title']){
           $thread_data['title'] = Helper::convert_to_title($thread_data['title']);
        }
        while(Helper::convert_to_public($thread_data['brief'])!=$thread_data['brief']){
           $thread_data['brief'] = Helper::convert_to_public($thread_data['brief']);
        }
        $thread_data['noreply']=$this->noreply ? true:false;
        $post_data['markdown']=$this->markdown ? true:false;
        $post_data['indentation']=$this->indentation ? true:false;
        $thread_data['lastresponded_at']=Carbon::now();
        $thread_data['user_id'] = auth()->id();
        $post_data['user_id'] = auth()->id();

        if (!$this->isDuplicateThread($thread_data)){
            $thread = DB::transaction(function () use($thread_data, $post_data) {
                $thread = Thread::create($thread_data);
                $post_data['thread_id'] = $thread->id;
                $post = Post::create($post_data);
                $thread->update(['post_id'=>$post->id]);
                $thread->update_channel();
                return $thread;
            });
        }else{
            abort(400,'请求已登记，请勿重复提交相同数据');
        }
        return $thread;
    }

    public function isDuplicateThread($data)
    {
        $last_thread = Thread::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->first();
        return count($last_thread) && strcmp($last_thread->title.$last_thread->brief.$last_thread->body, $data['title'].$data['brief'].$data['body']) === 0;
    }

    public function updateThread(Thread $thread)
    {
        $thread_data = $this->only('title','brief','body');
        $thread_data['label_id']=(int)$this->label;
        //查看标签是否符合权限
        if(\App\Models\Label::find($thread_data['label_id'])->channel_id!=$thread->channel_id){
            abort(403,'数据冲突');
        }
        //题目不能敏感
        while(Helper::convert_to_title($thread_data['title'])!=$thread_data['title']){
           $thread_data['title'] = Helper::convert_to_title($thread_data['title']);
        }
        while(Helper::convert_to_public($thread_data['brief'])!=$thread_data['brief']){
           $thread_data['brief'] = Helper::convert_to_public($thread_data['brief']);
        }
        //将boolean值赋予提交的设置
        $thread_data['anonymous']=$this->anonymous ? true:false;
        $thread_data['noreply']=$this->noreply ? true:false;
        $thread_data['edited_at']=Carbon::now();
        $post_data['markdown']=$this->markdown ? true:false;
        $post_data['indentation']=$this->indentation ? true:false;
        $thread->update($thread_data);
        $post = $thread->mainpost;
        $post->update($post_data);
        return $thread;
    }
}
