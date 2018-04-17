<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Thread;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            'body' => 'required|string|min:10',
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
        // $thread_data['public']=$this->public ? true:false;
        $thread_data['noreply']=$this->noreply ? true:false;
        $post_data['markdown']=$this->markdown ? true:false;
        $post_data['indentation']=$this->indentation ? true:false;
        $thread_data['lastresponded_at']=Carbon::now();
        $thread_data['user_id'] = auth()->id();
        $post_data['user_id'] = auth()->id();

        $thread = DB::transaction(function () use($thread_data, $post_data) {
            $thread = Thread::create($thread_data);
            $post_data['thread_id'] = $thread->id;
            $post = Post::create($post_data);
            $thread->update(['post_id'=>$post->id]);
            return $thread;
        }, 2);

        return $thread;

    }

    public function updateThread(Thread $thread)
    {
        $anonymous = request('anonymous')? true: false;
        $public = request('public')? true: false;
        $noreply = request('noreply')? true:false;
        $markdown = request('markdown')? true: false;
        $indentation = request('indentation')? true: false;
        $thread->update([
           'title' => request('title'),
           'brief' => request('brief'),
           'body' => request('body'),
           'label_id' => request('label'),
           'anonymous' => $anonymous,
           'public' => $public,
           'noreply' => $noreply,
           'edited_at' => Carbon::now(),
          ]);
          $post = $thread->mainpost;
          $post->update([
            'markdown'=>$markdown,
            'indentation'=>$indentation,
          ]);
    }
}
