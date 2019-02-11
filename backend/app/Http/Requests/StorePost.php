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
            'reply_position' => 'numeric',
            'reply_to_post_preview' => 'string',
            'is_anonymous' => 'boolean',
            'use_markdown' => 'boolean',
            'use_indentation' => 'boolean',
        ];
    }


    public function generatePost()
    {
        $post_data = $this->generatePostInfo();
        $reply_to_post = [];
        if($this->reply_to_post_id){
            $reply_to_post = Post::find($this->reply_to_post_id);
            if((!$reply_to_post)||($reply_to_post->thread_id!=$thread->id)){
                abort(482);
            }
            $post_data['reply_to_post_id'] = $this->reply_to_post_id;
            if($reply_to_post->type!='post'&&$reply_to_post->type!='comment'){
                $post_data['type'] = 'comment';//假如回复的是普通的
            }
            $post['reply_position'] = $this->reply_position ?: 0;
            $post['reply_to_post_preview'] = $this->reply_to_post_preview ?: StringProcess::trimtext($reply_to_post->body, config('constants.preview_len'));//没有正文的时候，只能后端计算并添加这个
        }
        $post = DB::transaction(function () use($post_data, $reply_to_post) {
            $post = Post::create($post_data);
            //这里还需要记录奖励历史信息？
            //递增被回复post的次数,给被回复方发放适当奖励？
            //奖励回帖人？
            if($reply_to_post){$reply_to_post->increment('replies');}//递增被回复人
            return $post;
        });
        return $post;
    }

    private function generatePostInfo()
    {
        $thread = request()->route('thread');
        $channel = $thread->channel();
        $post_data = $this->only('title', 'body', 'use_markdown', 'use_indentation', 'is_bianyuan', 'is_anonymous');
        $post_data['preview'] = $this->preview ?: StringProcess::trimtext($this->body, config('constants.preview_len'));
        if(!$channel->allow_anonymous){$post_data['is_anonymous']=false;}//如果channel不允许匿名，自动实名
        $post_data['thread_id'] = $thread->id;
        $post_data['creation_ip'] = request()->getClientIp();
        $post_data['type'] = 'post'; // add type
        if($thread->is_bianyuan){$post_data['is_bianyuan']=true;}
        $post_data['user_id'] = auth('api')->id();
        if ($this->isDuplicatePost($post_data)){ abort(409); }
        if($channel->type==='box'){
            if($thread->user_id!=auth('api')->id()){//假如不是自己的提问箱
                $last_question = Post::where('user_id',auth('api')->id())->where('type', 'question')->orderBy('created_at', 'desc')->first();
                if($last_question&&$last_question->created_at>Carbon::today()){
                    abort(410);//一个人一天只能给别人提一个问题
                }
            }
            if(!$this->reply_to_post_id>0){
                $post_data['type'] = 'question';//允许提问的时候，假如并非回复它人的post，那么这就是一个新的问题
            }
        }
        return $post_data;
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
        if($post->type==='question'){abort(403);}//问题箱里的问题，不能进行修改
        $post_data = $this->only('body', 'title', 'is_anonymous', 'use_markdown', 'use_indentation');
        $post_data['preview'] = $this->preview ?: StringProcess::trimtext($this->body, config('constants.preview_len'));
        if (!$channel->allow_anonymous){$post_data['is_anonymous']=false;}
        $post_data['last_edited_at']=Carbon::now();
        $post->update($post_data);
        return $post;
    }
}
