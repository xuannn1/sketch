<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePost;

use Illuminate\Support\Facades\DB;
use App\Models\Thread;
use App\Models\Post;
use App\Events\NewPost;
use Carbon;
use Auth;
use CacheUser;

use App\Sosadfun\Traits\FindThreadTrait;
use App\Sosadfun\Traits\PostObjectTraits;



class PostsController extends Controller
{
    use PostObjectTraits;
    use FindThreadTrait;

    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    public function store(StorePost $form, Thread $thread)
    {
        if ((!Auth::user()->isAdmin())&&($thread->is_locked||((!$thread->is_public)&&($thread->user_id!=Auth::id())))){
            return back()->with('danger', '抱歉，本主题锁定或设为隐私，不能回帖');
        }

        $post = $form->storePost($thread);

        event(new NewPost($post));

        if($post->checklongcomment()){
            $this->user->reward('long_post');
            return back()->with('success', '您得到了长评奖励');
        }

        if($post->checkfirstpost()){
            $post->user->reward("first_post");
            return back()->with('success', '您得到了新章节率先回帖的奖励');

        }
        $post->user->reward("regular_post");
        return back()->with('success', '您已成功回帖');

    }
    public function edit(Post $post)
    {
        $thread=$post->thread;
        $channel=$thread->channel();

        if($post->user_id!=Auth::id()){abort(403);}

        if(($thread->is_locked||!$thread->channel()->allow_edit)&&(!Auth::user()->isAdmin())){abort(403);}

        if($post->type==='chapter'){
            $chapter = $post->chapter;
            if($chapter){
                return view('chapters.edit', compact('chapter','post','thread'));
            }
        }

        if($post->type==='review'){
            $review = $post->review;
            if($review){
                return view('reviews.edit', compact('review','post','thread'));
            }
        }
        return view('posts.post_edit', compact('post'));
    }

    public function update(StorePost $form, Post $post)
    {
        $thread=$post->thread;
        if ((Auth::user()->isAdmin())||((Auth::id() == $post->user_id)&&(!$thread->is_locked)&&($thread->channel()->allow_edit)&&($post->fold_state<=0))){
            $form->updatePost($post);
            $this->clearPostProfile($post->id);
            return redirect()->route('thread.showpost', $post->id)->with('success', '您已成功修改帖子');
        }else{
            abort(403);
        }
    }
    public function show($id)
    {
        $post = $this->postProfile($id);
        $thread = $this->findThread($post->thread_id);

        if(!$post||!$thread){
            abort(404);
        }

        $user = Auth::check()? CacheUser::Auser():'';
        $info = Auth::check()? CacheUser::Ainfo():'';

        if(!$thread->is_public||!$thread->channel()->is_public){
            if(!$user){
                return redirect()->route('login');
            }else{
                if(!$user->canSeePost($post)){
                    return redirect()->back()->with('warning', '不能看这个帖');
                }
            }
        }

        return view('posts.show',compact('post','thread','user','info'));
    }

    private function canSeePost($post, $thread)
    {

    }

    public function destroy($id){
        $post = Post::findOrFail($id);
        $thread=$post->thread;
        $channel = $thread->channel();
        if(!$thread||!$post||!$channel){abort(404);}
        if($post->user_id!=Auth::id()){abort(404);}
        if(!Auth::user()->isAdmin()&&($thread->is_locked||$post->fold_state>0)){abort(403);}

        if($post->type==='chapter'){
            $chapter = $post->chapter;
            if($chapter){
                $chapter->delete();
                $thread->reorder_chapters();
                $this->clearThreadChapterIndex($id);
            }
        }
        if($post->type==='review'){
            $review = $post->review;
            if($review){
                $review->delete();
                $this->clearThreadReviewIndex($id);
            }
        }

        $post->delete();
        $this->clearPostProfile($id);
        return redirect()->route('home')->with("success","已经删帖");
    }
    public function turn_to_post(Post $post)
    {
        $thread = $post->thread;
        if(!$thread||$post->user_id!=Auth::id()||$thread->user_id!=Auth::id()){abort(403);}

        if($post->type==='chapter'){
            $chapter = $post->chapter;
            if($chapter){
                $chapter->delete();
                $thread->reorder_chapters();
                $this->clearThreadChapterIndex($id);
            }
        }
        if($post->type==='review'){
            $review = $post->review;
            if($review){
                $review->delete();
                $this->clearThreadReviewIndex($id);
            }
        }
        $post->type='post';
        $post->edited_at = Carbon::now();
        $post->save();
        $this->clearPostProfile($id);
        return redirect()->route('post.show',$post->id)->with('success','已经成功转化成普通回帖');
    }
}
