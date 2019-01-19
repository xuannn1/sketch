<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// common
use Carbon\Carbon;
use App\Helpers\ConstantObjects;

// model
use App\Models\Thread;
use App\Models\Post;
use App\Models\Chapter;

// form request
use App\Http\Requests\StorePost;
use DB;

class StoreChapter extends StorePost
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // for post record
            'body' => 'required|string|max:20000',
            'majia' => 'string|max:10'
        ];
    }

    public function generateChapter()
    {
        // create post first
        $postid = $this->generatePost()->id;
        $chapter['post_id'] = $postid;
        $chapter['characters'] = mb_strlen($this->only('body')['body']);
        // connect with previous and next
        $previous_chapter_id = $this->only('previous_chapter_id');
        if (isset($previous_chapter_id['previous_chapter_id'])){
            $this->setPrevious($previous_chapter_id['previous_chapter_id'],$postid);
            $chapter['previous_chapter_id'] = $previous_chapter_id['previous_chapter_id'];
        }
        // save 
        $chapter_obj = DB::transaction(function () use($chapter) {
                $chapter_obj = Chapter::create($chapter);
                return $chapter_obj;
            });
        return $chapter_obj;
    }


    private function setPrevious($previous_chapter_id, $postid)
    {
        // check whether previous chapter exist 
        $chapter = Chapter::where('post_id','=',$previous_chapter_id)->first();
        $check_previous = ($chapter && ($chapter->next_chapter_id == 0));
        if (! $check_previous == 1){ abort(595); } 
        $chapter_data['next_chapter_id'] = $postid;
        $chapter->update($chapter_data);

        $this->updatePost($postid);
        return;
    }

    private function updatePost($postid)
    {
        // get corresponding post and update
        $post = Post::where('id','=', $postid)->first();
        if (!$post) {abort(404); }
        $data['last_edited_at']=Carbon::now();
        $post->update($data);
        return;
    }
}
