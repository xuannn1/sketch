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
        $thread = $this->thread();
        return (($thread->is_public)&&(!$thread->no_reply))||(auth('api')->id()===$thread->user_id)||(auth('api')->user()->canManageChannel($thread->channel_id));
    }

    public function thread()
    {
        return request()->route('thread');
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
            'brief' => 'string|max:50',
            'majia' => 'string|max:10',
            'reply_id' => 'numeric',
            'reply_position' => 'numeric',
            'reply_brief' => 'string',
            'is_anonymous' => 'boolean',
            'use_markdown' => 'boolean',
            'use_indentation' => 'boolean',
        ];
    }


    public function generatePost()
    {
        $post_data = $this->generatePostData();
        $post_data = $this->filterBox($post_data);
        $reply_to_post = [];
        if($this->reply_id){
            $reply_to_post = Post::find($this->reply_id);
            $post_data = $this->addReplyInfo($post_data, $reply_to_post);
        }

        $post = DB::transaction(function () use($post_data, $reply_to_post) {
            $post = Post::create($post_data);
            //这里还需要记录奖励历史信息？
            if($reply_to_post){$reply_to_post->increment('reply_count');}//递增被回复人
            return $post;
        });
        return $post;
    }

    public function generatePostData()
    {
        $post_data = $this->only('title', 'body', 'use_markdown', 'use_indentation', 'is_bianyuan', 'is_anonymous');
        if($this->isDuplicatePost($post_data)){abort(409);}
        $post_data['brief'] = $this->brief ?: StringProcess::trimtext($this->body, config('constants.brief_len'));
        if(!$this->thread()->channel()->allow_anonymous){$post_data['is_anonymous']=false;}//如果channel不允许匿名，自动实名
        $post_data['thread_id'] = $this->thread()->id;
        $post_data['creation_ip'] = request()->getClientIp();
        $post_data['type'] = 'post'; // add type
        $post_data['char_count'] = mb_strlen($this->body);
        if($this->thread()->is_bianyuan){$post_data['is_bianyuan']=true;}
        $post_data['user_id'] = auth('api')->id();
        return $post_data;
    }

    public function filterBox($post_data=[])
    {
        if($this->thread()->channel()->type==='box'){
            if($this->thread()->user_id!=auth('api')->id()){//假如不是自己的提问箱
                $last_question = Post::where('user_id',auth('api')->id())->where('type', 'question')->orderBy('created_at', 'desc')->first();
                if($last_question&&$last_question->created_at>Carbon::today()){
                    abort(410);//一个人一天只能给别人提一个问题
                }
            }
            if(!$this->reply_id>0){
                $post_data['type'] = 'question';//允许提问的时候，假如并非回复它人的post，那么这就是一个新的问题
            }
        }
        return $post_data;
    }

    public function addReplyInfo($post_data=[], $reply_to_post)
    {
        if((!$reply_to_post)||($reply_to_post->thread_id!=$this->thread()->id)){
            abort(482);
        }
        $post_data['reply_id'] = $this->reply_id;
        if($reply_to_post->type!='post'&&$reply_to_post->type!='comment'){
            $post_data['type'] = 'comment';//假如回复的是普通的
        }
        $post['reply_position'] = $this->reply_position ?: 0;
        $post['reply_brief'] = $this->reply_brief ?: StringProcess::trimtext($reply_to_post->body, config('constants.brief_len'));//没有正文的时候，只能后端计算并添加这个
    }

    public function isDuplicatePost($post_data)
    {
        $last_post = Post::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_post)) && (strcmp($last_post->body, $post_data['body']) === 0);
    }

    public function updatePost($post)
    {
        $this->canUpdatePost($post);
        $this->canNotUpdateQuestion($post);
        $post_data = $this->generateUpdatePostData();
        $post->update($post_data);
        return $post;
    }

    public function generateUpdatePostData()
    {
        $post_data = $this->only('body', 'title', 'is_anonymous', 'use_markdown', 'use_indentation');
        $post_data['brief'] = $this->brief ?: StringProcess::trimtext($this->body, config('constants.brief_len'));
        if (!$this->thread()->channel()->allow_anonymous){$post_data['is_anonymous']=false;}
        $post_data['char_count'] = mb_strlen($this->body);
        $post_data['edited_at']=Carbon::now();
        return $post_data;
    }

    public function canUpdatePost($post)
    {
        if($this->thread()->id!=$post->thread_id){abort(403);}
        //必须是这个thread和post匹配
        if(!($this->thread()->channel()->allow_edit||auth('api')->user()->inRole('admin'))){abort(403);}
        //必须channel允许更新
        if($post->user_id!=auth('api')->id()){abort(403);}
        //必须自己的post
    }
    public function canNotUpdateQuestion($post)
    {
        if($post->type==='question'){abort(403);}
        //问题箱里的问题，不能进行修改
    }
}
