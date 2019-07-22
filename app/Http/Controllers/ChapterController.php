<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreChapter;
use Auth;
use CacheUser;
use Carbon;
use App\Models\Thread;
use App\Events\NewPost;
use App\Models\Post;
use App\Models\Chapter;
use App\Sosadfun\Traits\ThreadObjectTraits;
use App\Sosadfun\Traits\PostObjectTraits;

class ChapterController extends Controller
{
    use ThreadObjectTraits;
    use PostObjectTraits;

    public function create($id)
    {
        $thread = Thread::find($id);
        $channel = $thread->channel();
        if(!$channel||!$thread||$channel->type!='book'||$thread->is_locked){
            abort(403);
        }
        return view('chapters.create', compact('thread'));
    }

    public function store($id, StoreChapter $form)
    {
        $thread = Thread::find($id);
        if ($thread->is_locked||$thread->user_id!=Auth::id()){
            abort(403);
        }

        $post = $form->generateChapter($thread);

        event(new NewPost($post));

        if($post->post_check('standard_chapter')){
            $post->user->reward("standard_chapter");
        }else{
            $post->user->reward("short_chapter");
        }
        $this->clearAllThread($id);

        return redirect()->route('post.show', $post->id)->with('success', '您已成功发布章节');
    }


    public function update($id, StoreChapter $form)
    {
        $post = Post::find($id);
        if(!$post){abort(404);}
        $chapter = $post->chapter;
        $thread = $post->thread;

        if(!$post||!$thread||!$chapter||($thread->is_locked&&!Auth::user()->isAdmin())){
            abort(403);
        }

        $post = $form->updateChapter($post, $thread);
        $thread->recalculate_characters();
        $this->clearAllThread($thread->id);
        $this->clearPostProfile($post->id);

        return redirect()->route('post.show', $id)->with('success','已经成功更新章节');

    }

    public function turn_to_chapter($id)
    {
        $post = Post::find($id);
        $thread=$post->thread;
        $channel=$thread->channel();
        if($post->user_id!=Auth::id()){abort(403);}
        if(($thread->is_locked||!$thread->channel()->allow_edit)&&(!Auth::user()->isAdmin())){abort(403);}
        if($post->chapter){abort(409);}

        $previous_chapter = $thread->last_component;
        $order_by = $previous_chapter? ($previous_chapter->chapter->order_by+1):0;

        $chapter = Chapter::create(['post_id'=>$post->id,'order_by'=>$order_by]);
        $post->type = 'chapter';
        $post->reply_to_id = 0;
        $post->reply_to_brief = '';
        $post->reply_to_position = 0;
        $post->edited_at = Carbon::now();
        $post->save();

        $thread->recalculate_characters();
        $thread->reorder_chapters();
        $this->clearAllThread($thread->id);
        $this->clearPostProfile($post->id);

        return view('chapters.edit', compact('chapter','post','thread'));
    }
}
