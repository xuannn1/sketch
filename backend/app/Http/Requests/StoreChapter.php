<?php

namespace App\Http\Requests;

use App\Http\Requests\StorePost;
//use App\Helpers\StringProcess;
// common
use Carbon\Carbon;
use App\Helpers\ConstantObjects;

// model
use App\Models\Thread;
use App\Models\Post;
use App\Models\Chapter;

// form request
use DB;

class StoreChapter extends StorePost
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        $thread = request()->route('thread');
        $channel = $thread->channel();
        return auth('api')->id()===$thread->user_id && $channel->type==='book' && !$thread->is_locked;
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'title' => 'required|string|max:30',
            'brief' => 'string|max:50',
            'body' => 'required|string|max:20000',
            'warning' => 'string|max:50',
            'annotation' => 'string|max:20000',
            'use_markdown' => 'boolean',
            'use_indentation' => 'boolean',
            'is_bianyuan' => 'boolean',
        ];
    }

    public function generateChapter()
    {
        $thread = request()->route('thread');
        $previous_chapter = $thread->last_chapter;
        $chapter_data = $this->generateChapterData($previous_chapter);

        // generate post first
        $post_data = $this->generatePostData();
        $post_data['type'] = 'chapter';
        if($thread->is_anonymous){$post_data['is_anonymous']=true;}

        // save 把所有东西放进transaction里
        $post = DB::transaction(function() use($post_data, $chapter_data, $previous_chapter, $thread){
            // create post first
            $post = Post::create($post_data);
            $chapter_data['post_id'] = $post->id;
            if (($previous_chapter)&&($previous_chapter->chapter)){
                $previous_chapter->chapter->update(['next_id'=>$post->id]);
            }
            $chapter = Chapter::create($chapter_data);
            $thread->last_component_id = $post->id;
            $thread->add_component_at = Carbon::now();
            $thread->total_char = $thread->count_char();
            $thread->save();
            return $post;
        });
        return $post;
    }

    public function generateChapterData($previous_chapter)
    {
        $chapter_data = $this->only('warning', 'annotation');
        if($previous_chapter){
            $chapter_data['previous_id'] = $previous_chapter->id;
            //考虑到也有可能前一个并不是chapter，比如是poll，留出兼容空间。
            if(($previous_chapter->type==='chapter')&&($previous_chapter->chapter)){
                $chapter_data['order_by'] = $previous_chapter->chapter->order_by + 1;
                $chapter_data['volumn_id'] = $previous_chapter->chapter->volumn_id; //默认跟前面的同一volumn
            }
        }
        return $chapter_data;
    }

    public function updateChapter($post)
    {
        $chapter = $post->chapter;
        if((!$post)||(!$chapter)){ abort(404);}

        $this->canUpdatePost($post);

        $post_data = $this->generateUpdatePostData();

        $chapter_data = $this->only('warning', 'annotation');

        $thread = $this->thread();
        $post = DB::transaction(function () use($post, $chapter, $post_data, $chapter_data, $thread) {
            $post->update($post_data);
            $chapter->update($chapter_data);
            $thread->total_char = $thread->count_char();
            $thread->save();
            return $post;
        });

        return $post;
    }
}
