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

use App\Sosadfun\Traits\PostObjectTraits;
use App\Sosadfun\Traits\ThreadObjectTraits;



class PostsController extends Controller
{
    use PostObjectTraits;
    use ThreadObjectTraits;

    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    public function store(StorePost $form, Thread $thread)
    {
        if ((!Auth::user()->isAdmin())&&($thread->is_locked||((!$thread->is_public)&&($thread->user_id!=Auth::id())))){
            return back()->with('danger', '本主题锁定或设为隐私，不能回帖');
        }
        if(Auth::user()->no_posting){
            return back()->with('danger', '您被禁言中，无法回帖');
        }

        $post = $form->storePost($thread);

        event(new NewPost($post));

        $msg = $post->reward_creation();
        if($post->parent&&$post->parent->type==='chapter'&&$post->parent->chapter&&$post->parent->chapter->next_id>0){ // 回复章节之后自动前往下一章（如果有的话）
            return redirect()->route('post.show', $post->parent->chapter->next_id)->with('success', $msg);
        }
        return back()->with('success', $msg);
    }
    public function edit(Post $post)
    {
        if(!$post){abort(404);}
        $thread=$post->thread;
        $channel = $thread->channel();
        if(!$thread||!$post||!$channel){abort(404);}
        if($post->user_id!=Auth::id()){abort(403);}
        if(($thread->is_locked||!$thread->channel()->allow_edit||$post->fold_state>0)&&(!Auth::user()->isAdmin())){abort(403);}

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
        $channel = $thread->channel();
        if(!$thread||!$post||!$channel){abort(404);}
        if($post->user_id!=Auth::id()){abort(403);}
        if(($thread->is_locked||!$thread->channel()->allow_edit||$post->fold_state>0)&&(!Auth::user()->isAdmin())){abort(403);}

        $form->updatePost($post);
        $this->clearPostProfile($post->id);
        return redirect()->route('thread.showpost', $post->id)->with('success', '您已成功修改帖子');
    }

    public function show($id)
    {
        $post = $this->postProfile($id);
        if(!$post){abort(404);}
        $thread = $this->findThread($post->thread_id);
        if(!$thread){abort(404);}

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

        $post->recordViewCount('Post');

        return view('posts.show',compact('post','thread','user','info'));
    }

    public function destroy($id){
        $post = Post::findOrFail($id);
        if(!$post){abort(404);}
        $thread=$post->thread;
        $channel = $thread->channel();
        if(!$thread||!$channel){abort(404);}
        if($post->user_id!=Auth::id()){abort(403);}
        if(($thread->is_locked||!$thread->channel()->allow_edit||$post->fold_state>0)&&(!Auth::user()->isAdmin())){abort(403);}

        if($post->type==='chapter'){
            $chapter = $post->chapter;
            if($chapter){
                $chapter->delete();
                $thread->reorder_chapters();
                $this->clearAllThread($thread->id);
            }
        }
        if($post->type==='review'){
            $review = $post->review;
            if($review){
                $review->delete();
                $this->clearAllThread($thread->id);
            }
        }

        $post->delete();
        $this->clearPostProfile($id);
        return redirect()->route('thread.show', $thread->id)->with("success","已经删帖");
    }
    public function turn_to_post(Post $post)
    {
        $thread = $post->thread;
        if(!$thread||$thread->user_id!=Auth::id()){abort(403);}

        if($post->type==='chapter'){
            $chapter = $post->chapter;
            if($chapter){
                $chapter->delete();
                $thread->reorder_chapters();
                $this->clearAllThread($thread->id);
            }
        }
        if($post->type==='review'){
            $review = $post->review;
            if($review){
                $review->delete();
                $this->clearAllThread($thread->id);
            }
        }
        $post->type='post';
        $post->edited_at = Carbon::now();
        $post->save();
        $this->clearPostProfile($post->id);
        return redirect()->route('post.show',$post->id)->with('success','已经成功转化成普通回帖');
    }

    public function delete_by_owner($id) //仅限问题箱主人可以这样做
    {
        $post = Post::findOrFail($id);
        if(!$post){abort(404);}
        $thread=$post->thread;
        if(!$thread||!$post){abort(404);}
        if($thread->is_locked||$thread->channel()->type!='box'||$thread->user_id!=Auth::id()||Auth::user()->no_posting){abort(403);}

        $post->delete();
        $this->clearPostProfile($id);
        return redirect()->route('thread.show', $thread->id)->with("success","已经删帖");
    }
    public function fold_by_owner($id)
    {
        $post = Post::findOrFail($id);
        if(!$post){abort(404);}
        $thread=$post->thread;
        if(!$thread||!$post){abort(404);}
        if($thread->is_locked||$thread->user_id!=Auth::id()||Auth::user()->no_posting){abort(403);}
        if($post->fold_state>0){return
            back()->with('warning','已经是折叠的贴，不能再处理');
        }

        $post->update(['fold_state'=>2]);
        if($post->reply_to_id>0){
            $this->clearPostProfile($post->reply_to_id);
        }
        return redirect()->route('thread.showpost', $post->id)->with("success","已经折叠该回帖");
    }

    public function reward($id)
    {
        $post = $this->findPost($id);
        if(!$post){abort(404);}
        $thread = $this->findThread($post->thread_id);
        if(!$thread){abort(404);}
        $info = CacheUser::Ainfo();
        return view('posts.reward_form', compact('post','thread','info'));
    }
}
