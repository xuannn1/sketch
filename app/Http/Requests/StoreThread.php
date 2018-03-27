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
        $data = $this->only('title','brief','body');
        $data2 = [];
        $data2['user_ip'] = $this->getClientIp();
        $data['channel_id']=$channel_id;
        $data['label_id']=(int)$this->label;
        //查看标签是否符合权限
        if(\App\Models\Label::find($data['label_id'])->channel_id!=$channel_id){
            return redirect()->route('error', ['error_code' => '403']);
        }
        //将boolean值赋予提交的设置
        if ($this->anonymous){
            $data['anonymous']=1;
            $data['majia']=$this->majia;
            auth()->user()->update(['majia'=>$this->majia]);
        }else{
            $data['anonymous']=0;
        }
        $data['public']=$this->public ? true:false;
        $data['noreply']=$this->noreply ? true:false;
        $data2['markdown']=$this->markdown ? true:false;
        $data2['indentation']=$this->indentation ? true:false;

        $data['lastresponded_at']=Carbon::now();
        $data['user_id'] = auth()->id();
        $thread = DB::transaction(function () use($data, $data2) {
            $thread = Thread::create($data);
            $data2['user_id'] = auth()->id();
            $data2['thread_id'] = $thread->id;
            $post = Post::create($data2);
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
