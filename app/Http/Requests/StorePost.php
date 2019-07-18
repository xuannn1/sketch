<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Helper;
use App\Models\Chapter;
use App\Models\Thread;
use App\Models\Post;
use Carbon;
use DB;
use StringProcess;

class StorePost extends FormRequest
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
            'body' => 'required|string|min:10|max:20000',
            'reply_to_id' => 'numeric',
            'majia' => 'string|max:10',
            'title' => 'string|nullable|max:25',
        ];
    }

    public function generatePost(Thread $thread){
        $user = auth()->user();
        $data = $this->only('body');
        $data['body'] = StringProcess::trimSpaces($data['body']);
        if ($this->isDuplicatePost($data)){
            abort(400,'请求已登记，请勿重复提交相同数据');
        }
        $data['brief']=StringProcess::trimtext($data['body'], 45);
        $data['creation_ip'] = request()->ip();
        $data['char_count'] = iconv_strlen($data['body'], 'utf-8');
        if ($this->is_anonymous&&$thread->channel()->allow_anonymous){
            $data['is_anonymous']=1;
            $data['majia']=$this->majia;
        }else{
            $data['is_anonymous']=0;
        }
        // $data['use_markdown']=$this->use_markdown ? true:false;
        $data['is_bianyuan']=$thread->is_bianyuan ? true:false;
        $data['use_indentation']=$this->use_indentation ? true:false;
        if($this->reply_to_id>0){
            $reply = Post::find($this->reply_to_id);
            if($reply){
                $data['reply_to_id'] = $reply->id;
                $data['reply_to_brief'] = $reply->brief;
                $data['is_bianyuan']=$data['is_bianyuan']||$reply->is_bianyuan;
                if($reply->type==='post'||$reply->type==='comment'){
                    $data['in_component_id'] = $reply->in_component_id;
                    $data['type'] = 'comment';
                }else{
                    $data['in_component_id'] = $reply->id;
                    $data['type'] = 'post';
                }
            }
        }
        $data['user_id']=$user->id;
        $data['thread_id']= $thread->id;

        $post = Post::create($data);

        return $post;
    }

    public function isDuplicatePost($data)
    {
        $last_post = Post::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->first();
        return !empty($last_post) && strcmp($last_post->body, $data['body']) === 0;
    }

    public function updatePost(Post $post)
    {
        $data = $this->only('body','title');
        $data['body'] = StringProcess::trimSpaces($data['body']);
        $data['char_count'] = iconv_strlen($data['body'], 'utf-8');
        $data['brief']=StringProcess::trimtext($data['body'], 45);
        $data['is_anonymous']=$this->is_anonymous&&$post->thread->channel()->allow_anonymous ? 1:0;
        // $data['use_markdown']=$this->use_markdown ? true:false;
        $data['use_indentation']=$this->use_indentation ? true:false;
        auth()->user()->info->update(['use_indentation'=>$data['use_indentation']]);
        $data['edited_at']=Carbon::now();
        $post->update($data);
        return $post;
    }
}
