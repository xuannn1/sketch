<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreChapter;
use DB;
use Auth;
use CacheUser;
use App\Models\Thread;
use App\Events\NewPost;
use App\Sosadfun\Traits\ThreadObjectTraits;

class ChapterController extends Controller
{
    use ThreadObjectTraits;

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


    public function edit($id)
    {
        $chapter = Chapter::find($id);
        $post = $chapter->mainpost;
        $thread = $post->thread;
        $channel = $thread->channel();
        if(!$channel||!$thread||$channel->type!='book'||$thread->is_locked){
            abort(403);
        }
        return view('chapters.edit', compact('chapter','post','thread'));

    }
    public function update($id)
    {
        $chapter = Chapter::find($id);
        $post = $chapter->mainpost;
        $thread = $post->thread;
        $channel = $thread->channel();
        if(!$channel||!$thread||$channel->type!='book'||$thread->is_locked){
            abort(403);
        }

        $post = $form->updateChapter($post, $thread);

        $this->clearThreadProfile($thread->id);
        $this->clearThreadChapterIndex($thread->id);

        return redirect()->route('post.show', $id)->with('success','已经成功更新章节');

    }
}
