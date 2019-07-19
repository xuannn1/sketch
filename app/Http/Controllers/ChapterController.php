<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreChapter;
use Auth;
use CacheUser;
use App\Models\Thread;
use App\Events\NewPost;
use App\Models\Post;
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

        if($post->checklongchapter()){
            $post->user->reward("standard_chapter");
        }else{
            $post->user->reward("short_chapter");
        }
        $this->clearThreadProfile($thread->id);
        $this->clearThreadChapterIndex($thread->id);

        return redirect()->route('post.show', $post->id)->with('success', '您已成功发布章节');
    }


    public function update($id, StoreChapter $form)
    {
        $post = Post::find($id);
        $chapter = $post->chapter;
        $thread = $post->thread;

        if(!$post||!$thread||!$chapter||($thread->is_locked&&!Auth::user()->isAdmin())){
            abort(403);
        }

        $post = $form->updateChapter($post, $thread);
        $thread->recalculate_characters();
        $this->clearPostProfile($id);
        $this->clearThreadChapterIndex($id);

        return redirect()->route('post.show', $id)->with('success','已经成功更新章节');

    }
}
