<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;
use StringProcess;
use App\Models\Thread;
use App\Models\Chapter;
use App\Models\Post;
use Carbon;
use App\Sosadfun\Traits\GeneratePostDataTraits;

class StoreChapter extends FormRequest
{
    use GeneratePostDataTraits;
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
            'title' => 'required|string|max:25',
            'brief' => 'max:40',
            'body' => 'required|string|min:15|max:20000',
            'annotation' => 'max:2000',
            'warning' => 'max:500',
        ];
    }

    public function generateChapter($thread){
        $post_data = $this->generatePostData($thread);
        $post_data['type'] = 'chapter';
        $post_data['brief'] = $this->brief;
        $post_data['is_anonymous']=$thread->is_anonymous;
        $post_data['majia']=$thread->majia;
        if($this->is_bianyuan){
            $post_data['is_bianyuan']=true;
        }

        // chapter data
        $chapter_data = $this->only('warning','annotation');
        $chapter_data['annotation']=StringProcess::trimSpaces($chapter_data['annotation']);
        $chapter_data['warning']=StringProcess::trimSpaces($chapter_data['warning']);
        $max_order_by = $thread->max_chapter_order();
        $chapter_data['order_by'] = $max_order_by ? $max_order_by+1 : 1;
        $post = DB::transaction(function()use($post_data, $chapter_data){
            $post = Post::create($post_data);
            $chapter_data['post_id']=$post->id;
            $chapter = Chapter::create($chapter_data);
            return $post;
        });
        return $post;
    }

    public function updateChapter($post, $thread)
    {
        $chapter = $post->chapter;
        $post_data = $this->generateUpdatePostData($post);
        $post_data['brief'] = $this->brief;
        $post_data['is_anonymous']=$thread->is_anonymous;
        if($this->is_bianyuan){
            $post_data['is_bianyuan']=true;
        }
        $chapter_data = $this->only('warning','annotation');
        $chapter_data['annotation']=StringProcess::trimSpaces($chapter_data['annotation']);
        $chapter_data['warning']=StringProcess::trimSpaces($chapter_data['warning']);

        $post->update($post_data);
        $chapter->update($chapter_data);

        return $post;

    }
}
