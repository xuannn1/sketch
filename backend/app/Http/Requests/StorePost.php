<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Post;
use App\Models\Thread;
use App\Helpers\StringProcess;
use App\Helpers\ConstantObjects;
use DB;

class StorePost extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        $thread = request()->route('thread');

        return (($thread->is_public)&&(!$thread->no_reply))||(auth('api')->id()===$thread->user_id)||(auth('api')->user()->canManageChannel($thread->channel_id));
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'body' => 'required|string|max:20000',
            'majia' => 'string|max:10',
            'reply_to_post_id' => 'numeric',
        ];
    }


    public function generatePost()
    {
        $thread = request()->route('thread');
        $channel = ConstantObjects::allChannels()->keyBy('id')->get($thread->channel_id);
        $post = $this->only('body');
        $post['thread_id'] = $thread->id;
        $post['preview'] = StringProcess::trimtext($post['body'], config('constants.preview_len'));//这里存放节选的正文
        $post['creation_ip'] = request()->getClientIp();
        if (($this->is_anonymous)&&($channel->allow_anonymous)){
            $post['is_anonymous']=1;
            $post['majia']=$this->majia;
            auth('api')->user()->update(['majia'=>$this->recent_majia]);
        }else{
            $post['is_anonymous']=0;
        }
        if($this->reply_to_post_id){
            $reply_to_post = Post::find($this->reply_to_post_id);
            if((!$reply_to_post)||($reply_to_post->thread_id!=$thread->id)){
                abort(482);
            }
            $post['reply_to_post_id'] = $this->reply_to_post_id;
            //增加其他的内容：preview；
            //递增这个post被回复的次数
            //下面是一个待实现功能：将回复对象所在的准确段落摘选出来
            //$post['reply_position'] = $this->reply_position ?? 0;
            //$post['reply_to_post_preview'] =
        }

        $post['use_markdown']=$this->use_markdown ? true:false;
        $post['use_indentation']=$this->use_indentation ? true:false;
        $post['is_bianyuan']=$this->is_bianyuan ? true:false;
        $post['last_responded_at']=Carbon::now();
        $post['user_id'] = auth('api')->id();
        if (!$this->isDuplicatePost($post)){
            $post = DB::transaction(function () use($post) {
                $post = Post::create($post);
                //这里还需要记录奖励历史信息
                //递增被回复post的次数,给被回复方发放适当奖励
                //奖励回帖人
                return $post;
            });
        }else{
            abort(409);
        }
        return $post;
    }

    public function isDuplicatePost($post)
    {
        $last_post = Post::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_post)) && (strcmp($last_post->body, $post['body']) === 0);
    }
}
