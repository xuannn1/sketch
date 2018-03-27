<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Chapter;
use App\Models\Thread;
use App\Models\Post;

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
            'body' => 'required|string|min:10',
            'reply_to_post_id' => 'numeric',
            'majia' => 'string|max:10',
        ];
    }

    public function generatePost(Thread $thread){
        $data = $this->only('body');
        $data['user_ip'] = $this->getClientIp();
        if ($this->anonymous){
            $data['anonymous']=1;
            $data['majia']=$this->majia;
        }else{
            $data['anonymous']=0;
        }
        $data['markdown']=$this->markdown ? true:false;
        $data['indentation']=$this->indentation ? true:false;

        $data['chapter_id'] = (int)$this->default_chapter_id;
        if ($data['chapter_id']!=0){
            $chapter = Chapter::find($data['chapter_id']);
            if ((!$chapter)||($thread->book_id == 0)||($chapter->book_id != $thread->book->id)){
             return redirect()->route('error', ['error_code' => '403']);
            }
        }

        $data['reply_to_post_id'] = (int)$this->reply_to_post_id;
        if ($data['reply_to_post_id']!= 0){
            $reply = Post::find($data['reply_to_post_id']);
            if ((!$reply)||($reply->thread_id != $thread->id)){
             return redirect()->route('error', ['error_code' => '403']);
            }
            if($reply->maintext){//假如回复的是某章节，
             $data['reply_to_post_id'] = 0;
            }
            $data['chapter_id'] = $reply->chapter_id;
        }
        $data['user_id']=auth()->id();
        $data['thread_id']= $thread->id;

        $post = Post::create($data);
        return $post;
    }

    public function updatePost(Post $post)
    {

        $data = $this->only('body');
        if ($this->anonymous){
            $data['anonymous']=1;
        }else{
            $data['anonymous']=0;
        }
        $data['markdown']=$this->markdown ? true:false;
        $data['indentation']=$this->indentation ? true:false;

        $post->update($data);
        return $post;
    }
}
