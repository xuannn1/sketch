<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Helper;
use App\Models\Chapter;
use App\Models\Thread;
use App\Models\Post;
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
        $data['body'] = Helper::trimSpaces($data['body']);
        if ($this->isDuplicatePost($data)){
            abort(400,'请求已登记，请勿重复提交相同数据');
        }
        $data['brief']=Helper::trimtext($data['body'], 45);
        $data['creation_ip'] = request()->ip();
        $data['char_count'] = iconv_strlen($data['body'], 'utf-8');
        if ($this->anonymous&&$thread->channel()->allow_anonymous){
            $data['anonymous']=1;
            $data['majia']=$this->majia;
        }else{
            $data['anonymous']=0;
        }
        $data['markdown']=$this->markdown ? true:false;
        $data['indentation']=$this->indentation ? true:false;
        if($this->reply_to_id>0){
            $reply = Post::find($this->reply_to_id);
            if($reply){
                $data['reply_to_id'] = $reply->id;
                $data['reply_to_brief'] = $reply->brief;
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
        $post = DB::transaction(function () use($user, $data){
            $post = Post::create($data);
            $user->info->update(['indentation'=>$data['indentation']]);
            if($data['anonymous']){
                $user->update([
                    'majia'=>$data['majia'],
                    'indentation' => $data['indentation']
                ]);
            }
            return $post;
        });



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
        $data['body'] = Helper::trimSpaces($data['body']);
        $data['char_count'] = iconv_strlen($data['body'], 'utf-8');
        $data['brief']=Helper::trimtext($data['body'], 45);
        $data['anonymous']=$this->anonymous&&$post->thread->channel()->allow_anonymous ? 1:0;
        $data['markdown']=$this->markdown ? true:false;
        $data['indentation']=$this->indentation ? true:false;
        auth()->user()->info->update(['indentation'=>$data['indentation']]);
        $data['edited_at']=Carbon::now();
        $post->update($data);
        return $post;
    }
}
