<?php

namespace App\Sosadfun\Traits;

use App\Models\Thread;
use StringProcess;
use Carbon;

trait GenerateThreadDataTraits{
    public function generateThreadData($channel)
    {
        $thread_data = $this->only('title','brief','body');
        $thread_data['body'] = StringProcess::trimSpaces($thread_data['body']);
        $thread_data['creation_ip'] = request()->getClientIp();
        $thread_data['channel_id']=$channel->id;
        $thread_data['is_anonymous']=0;
        $thread_data['no_reply']=$this->no_reply ? true:false;
        $thread_data['use_markdown']=$this->use_markdown ? true:false;
        $thread_data['use_indentation']=$this->use_indentation ? true:false;
        $thread_data['is_bianyuan']=$this->is_bianyuan==='is'? true:false;
        $thread_data['is_public']=$this->is_public ? true:false;
        $thread_data['responded_at']=Carbon::now();
        $thread_data['user_id'] = auth()->id();

        // 将boolean值赋予提交的设置
        if ($this->is_anonymous&&$channel->allow_anonymous){
            $thread_data['is_anonymous']=1;
            $thread_data['majia']=$this->majia;
        }
        while(StringProcess::convert_to_public($thread_data['title'])!=$thread_data['title']){
           $thread_data['title'] = StringProcess::convert_to_public($thread_data['title']);
        }
        while(StringProcess::convert_to_public($thread_data['brief'])!=$thread_data['brief']){
           $thread_data['brief'] = StringProcess::convert_to_public($thread_data['brief']);
        }
        if(!$thread_data['title']){
            abort('409','标题违规词超标');
        }

        if ($this->isDuplicateThread($thread_data)){
            abort(409,'您已经成功建立相关主题，请从个人主页找到已经建立的内容，不要重复建立主题！');
        }
        return $thread_data;
    }

    public function isDuplicateThread($thread_data)
    {
        $last_thread = Thread::where('user_id', auth()->id())
        ->where('created_at','>',Carbon::now()->subDays(1))
        ->orderBy('id', 'desc')
        ->first();
        return  !empty($last_thread) && strcmp($last_thread->title, $thread_data['title']) === 0;
    }

    public function generateUpdateThreadData($thread)
    {
        $channel = $thread->channel();
        $thread_data = $this->only('title','brief','body');
        $thread_data['body'] = StringProcess::trimSpaces($thread_data['body']);
        $thread_data['is_anonymous']=0;
        $thread_data['no_reply']=$this->no_reply ? true:false;
        $thread_data['use_markdown']=$this->use_markdown ? true:false;
        $thread_data['use_indentation']=$this->use_indentation ? true:false;
        $thread_data['is_bianyuan']=$this->is_bianyuan==='is'? true:false;
        $thread_data['is_public']=$this->is_public ? true:false;
        $thread_data['edited_at']=Carbon::now();

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
        return $thread_data;

    }

    public function all_tags()
    {
        $tags = [];
        $tags = array_merge($tags,array($this->primary_tag,$this->sexual_orientation_tag, $this->book_length_tag, $this->book_status_tag, $this->tongren_yuanzhu_tag_id, $this->tongren_CP_tag_id));
        $tags = array_merge($tags,$this->tags);
        return $tags;
    }

}
