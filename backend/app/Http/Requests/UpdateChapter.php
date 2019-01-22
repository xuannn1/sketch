<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\UpdatePost;

use App\Models\Post;
use App\Models\Chapter;

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
      $channel = $thread->channel; //todo
      // $post = request()->route('post');
      return (auth('api')->id()===$thread->user_id);
    }

    public function updateChapter($postid){
        
        // 更新volumn也可以放在这里
        $post = Post::find($postid);
        if (!$post){ abort(404);}
        $data['body'] = $this->only('body')['body'];
        $post->update($data);
        
        // 更新chapter
        $chapter = Chapter::find($postid);
        if (!$chapter) { abort(404); } // this situation should never happen
        $chapterdata['characters'] = mb_strlen($this->only('body')['body']);
        $chapter->update($chapterdata);

        return $chapter;
    }
}
