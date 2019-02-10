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
            'body' => 'string|max:20000',
            'title' => 'string|max:50',
            'preview' => 'string|max:50',
            'majia' => 'string|max:10',
            'reply_to_post_id' => 'numeric',
            'is_anonymous' => 'boolean',
            'use_markdown' => 'boolean',
            'use_indentation' => 'boolean',
            'allow_as_longpost' => 'boolean',
        ];
    }


    public function generatePost()
    {
        $thread = request()->route('thread');
        $channel = $thread->channel();
        $post_data = $this->only('body', 'title', 'preview', 'majia', 'is_anonymous', 'use_markdown', 'use_indentation', 'allow_as_longpost');
        $post_data['thread_id'] = $thread->id;
        $post_data['creation_ip'] = request()->getClientIp();
        if (!$channel->allow_anonymous){$post_data['is_anonymous']=false;}//如果channel不允许匿名，自动实名
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
        if($thread->is_bianyuan){$post_data['is_bianyuan']=false;}
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

    public function updatePost($id)
    {
        $post = Post::find($id);
        $thread = request()->route('thread');
        if($thread->id!=$post->thread_id){abort(403);}
        $channel = $thread->channel();
        if(!($channel->allow_edit||auth('api')->user()->inRole('admin'))||($post->user_id!=auth('api')->id())){abort(403);}
        if($post->type==='question'){abort(403);}//问题箱里已经回答了的问题，不能进行修改
        $post_data = $this->only('body', 'title', 'preview', 'is_anonymous', 'use_markdown', 'use_indentation', 'allow_as_longpost');
        if (!$channel->allow_anonymous){$post_data['is_anonymous']=false;}
        $post_data['last_edited_at']=Carbon::now();
        $post->update($post_data);
        return $post;
    }
}
