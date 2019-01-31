<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

use Carbon\Carbon;

use App\Models\Post;
use App\Models\Chapter;

use DB;

class UpdateChapter extends UpdatePost
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      $thread = request()->route('thread');
      return auth('api')->id()===$thread->user_id;
    }

    public function rules()
    {
        return [
            'title' => 'string|max:30',
            'preview' => 'string|max:50',
            'body' => 'string|max:20000',
            'warning' => 'string|max:50',
            'annotation' => 'string|max:20000',
            'use_markdown' => 'boolean',
            'use_indentation' => 'boolean',
            'is_bianyuan' => 'boolean',
        ];
    }

    public function updateChapter($id)
    {
        $thread = request()->route('thread');
        //validate if model exists
        $post = Post::find($id);
        $chapter = Chapter::find($id);
        if ((!$post)||(!$chapter)){ abort(404);}

        //generate post data
        $post_data = $this->only('body', 'title', 'preview', 'use_markdown', 'use_indentation', 'is_bianyuan');
        if($thread->is_bianyuan){$post_data['is_bianyuan']=true;}
        $post_data['last_edited_at'] = Carbon::now();

        //generate chapter data
        $chapter_data = $this->only('warning', 'annotation');
        $chapter_data['characters'] = mb_strlen($this->body);

        //use transaction to update models
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
