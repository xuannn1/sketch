<?php

namespace App\Sosadfun\Traits;

use App\Models\Post;
use StringProcess;
use Carbon;

trait GeneratePostDataTraits{
    use PostObjectTraits;
    public function generatePostData($thread)
    {
        $data = $this->only('body','title');
        $data['body'] = StringProcess::trimSpaces($data['body']);
        if ($this->isDuplicatePost($data)){
            abort(409,'请求已登记，请勿重复提交相同数据');
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
        if($this->is_comment&&$this->reply_to_id>0){$data['type']='comment';}
        if ($this->is_anonymous&&$thread->channel()->allow_anonymous){
            $data['is_anonymous']=1;
            $data['majia']=$this->majia;
        }
        if($thread->channel()->type==='box'&&$thread->user_id!=auth()->id()){
            $data['type']='question';
        }
        return $data;
    }

    public function isDuplicatePost($data)
    {
        $last_post = Post::on('mysql::write')->where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->first();
        return !empty($last_post) && strcmp($last_post->body, $data['body']) === 0;
    }

    public function addReplyData($data, $thread)
    {
        if($this->reply_to_id>0){
            $reply = $this->findPost($this->reply_to_id);
            if($reply){
                $data['reply_to_id'] = $reply->id;
                $data['reply_to_brief'] = $reply->brief;
                $data['is_bianyuan']=$data['is_bianyuan']||$reply->is_bianyuan;
                $data['in_component_id'] = $reply->in_component_id>0?$reply->in_component_id:$reply->id;
                if(($reply->type==='post'&&$data['char_count']<50)||$reply->type==='comment'){
                    $data['type'] = 'comment';
                }
                if($reply->type==='question'&&$thread->user_id===auth()->id()){
                    $data['type'] = 'answer';
                }
            }
        }
        return $data;
    }

    public function generateUpdatePostData($post)
    {
        $data = $this->only('body','title');
        $data['body'] = StringProcess::trimSpaces($data['body']);
        $data['char_count'] = iconv_strlen($data['body'], 'utf-8');
        $data['brief']=StringProcess::trimtext($data['body'], 45);
        $data['is_anonymous']=$this->is_anonymous&&$post->thread->channel()->allow_anonymous ? 1:0;
        $data['use_markdown']=$this->use_markdown ? true:false;
        $data['use_indentation']=$this->use_indentation ? true:false;
        $data['edited_at']=Carbon::now();
        if($post->reply_to_id>0&&($post->type==="comment"||$post->type==="post")){
            $data['type']=$this->is_comment? 'comment':'post';
        }
        return $data;
    }

    public function check_length($old_post,$post)
    {
        if($old_post->char_count>config('constants.longcomment_length')&&$post->char_count<config('constants.longcomment_length'))
        $post->user->retract('reduce_long_to_short');
    }
}
