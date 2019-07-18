<?php

namespace App\Sosadfun\Traits;

use App\Models\Post;
use StringProcess;

trait GeneratePostDataTraits{
    public function generatePostData($thread)
    {
        $data = $this->only('body','title');
        $data['body'] = StringProcess::trimSpaces($data['body']);
        if ($this->isDuplicatePost($data)){
            abort(400,'请求已登记，请勿重复提交相同数据');
        }
        $data['brief']=StringProcess::trimtext($data['body'], 45);
        $data['creation_ip'] = request()->ip();
        $data['char_count'] = iconv_strlen($data['body'], 'utf-8');
        $data['is_bianyuan']=$thread->is_bianyuan ? true:false;
        $data['use_markdown']=$this->use_markdown ? true:false;
        $data['use_indentation']=$this->use_indentation ? true:false;
        $data['user_id']=auth()->id();
        $data['thread_id']= $thread->id;
        $data['is_anonymous']=0;
        $data['type']='post';
        if ($this->is_anonymous&&$thread->channel()->allow_anonymous){
            $data['is_anonymous']=1;
            $data['majia']=$this->majia;
        }
        return $data;
    }

    public function isDuplicatePost($data)
    {
        $last_post = Post::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->first();
        return !empty($last_post) && strcmp($last_post->body, $data['body']) === 0;
    }

    public function addReplyData($data)
    {
        if($this->reply_to_id>0){
            $reply = Post::find($this->reply_to_id);
            if($reply){
                $data['reply_to_id'] = $reply->id;
                $data['reply_to_brief'] = $reply->brief;
                $data['is_bianyuan']=$data['is_bianyuan']||$reply->is_bianyuan;
                if($reply->type==='post'||$reply->type==='comment'){
                    $data['in_component_id'] = $reply->in_component_id;
                    $data['type'] = 'comment';
                }
            }
        }
        return $data;
    }
}
