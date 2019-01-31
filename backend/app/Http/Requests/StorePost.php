<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Post;
use App\Models\Thread;
use App\Helpers\StringProcess;
use App\Helpers\ConstantObjects;

use Carbon\Carbon;
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
            'preview' => 'string|max:50',
            'majia' => 'string|max:10',
            'reply_to_post_id' => 'numeric',
        ];
    }


    public function generatePost()
    {
        $thread = request()->route('thread');
        $channel = ConstantObjects::allChannels()->keyBy('id')->get($thread->channel_id);
        $post_data = $this->only('body', 'preview');
        $post_data['thread_id'] = $thread->id;
        $post_data['creation_ip'] = request()->getClientIp();
        if (($this->is_anonymous)&&($channel->allow_anonymous)){
            $post_data['is_anonymous']=true;
            $post_data['majia']=$this->majia;
        }else{
            $post_data['is_anonymous']=false;
        }
        if($this->reply_to_post_id){
            $reply_to_post = Post::find($this->reply_to_post_id);
            if((!$reply_to_post)||($reply_to_post->thread_id!=$thread->id)){
                abort(482);
            }
            $post_data['reply_to_post_id'] = $this->reply_to_post_id;
            //增加其他的内容：preview；
            //递增这个post被回复的次数
            //下面是一个待实现功能：将回复对象所在的准确段落摘选出来
            //$post['reply_position'] = $this->reply_position ?? 0;
            //$post['reply_to_post_preview'] =
        }

        $post_data['use_markdown']=$this->use_markdown ? true:false;
        $post_data['use_indentation']=$this->use_indentation ? true:false;
        $post_data['is_bianyuan']=($this->is_bianyuan||$thread->is_bianyuan) ? true:false;
        //这一项还需要改进，是否给任意的回帖人将帖子变成边缘类型的权限，有待考虑
        //$post['allow_as_longpost']=$this->allow_as_longpost ? true:false;
        $post_data['last_responded_at']=Carbon::now();
        $post_data['user_id'] = auth('api')->id();
        $post_data['type'] = 'post';
        if (!$this->isDuplicatePost($post_data)){
            $post = DB::transaction(function () use($post_data) {
                $post = Post::create($post_data);
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

    public function isDuplicatePost($post_data)
    {
        $last_post = Post::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_post)) && (strcmp($last_post->body, $post_data['body']) === 0);
    }
}
