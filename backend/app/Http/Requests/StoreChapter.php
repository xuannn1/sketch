<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

// common
use Carbon\Carbon;
use App\Helpers\ConstantObjects;

// model
use App\Models\Thread;
use App\Models\Post;
use App\Models\Chapter;

// form request
use DB;

class StoreChapter extends FormRequest
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
        return auth('api')->id()===$thread->user_id && $channel->type==='book';
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
            'preview' => 'string|max:50',
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

        //generate chapter info
        $previous_chapter = $thread->last_chapter;
        $chapter_data = $this->only('warning', 'annotation');
        if(($previous_chapter)&&($previous_chapter->chapter)){
            $chapter_data['order_by'] = $previous_chapter->chapter->order_by + 1;
            $chapter_data['previous_chapter_id'] = $previous_chapter->id;
            $chapter_data['volumn_id'] = $previous_chapter->chapter->volumn_id; //默认跟前面的同一volumn
        }
        $chapter_data['characters'] = mb_strlen($this->body);

        // generate post first
        $post_data = $this->generatePostInfo();

        // save 把所有东西放进transaction里
        $post = DB::transaction(function() use($post_data, $chapter_data, $previous_chapter, $thread){
            // create post first
            $post = Post::create($post_data);
            $chapter_data['post_id'] = $post->id;
            if (($previous_chapter)&&($previous_chapter->chapter)){
                $previous_chapter->chapter->update(['next_chapter_id'=>$post->id]);
            }
            $chapter = Chapter::create($chapter_data);
            $thread->last_component_id = $post->id;
            $thread->last_added_component_at = Carbon::now();
            $thread->total_char = $thread->count_char();
            $thread->save();
            return $post;
        });
        return $post;
    }

    private function generatePostInfo()
    {
        $thread = request()->route('thread');
        $post_data = $this->only('title','preview','body','use_markdown','use_indentation','is_bianyuan');
        $post_data['thread_id'] = $thread->id;
        $post_data['creation_ip'] = request()->getClientIp();
        $post_data['type'] = 'chapter'; // add type
        $post_data['is_anonymous']=$thread->is_anonymous;
        if($thread->is_bianyuan){$post_data['is_bianyuan']=true;}
        $post_data['user_id'] = auth('api')->id();
        if ($this->isDuplicatePost($post_data)){ abort(409); }
        return $post_data;
    }

    private function isDuplicatePost($post_data)
    {
        $last_post = Post::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_post)) && (strcmp($last_post->body, $post_data['body']) === 0);
    }
}
