<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePost;

use Illuminate\Support\Facades\DB;
use App\Models\Thread;
use App\Models\Post;
use App\Events\NewPost;
use Carbon\Carbon;
use Auth;
use App\Models\Chapter;
use App\Models\Activity;
use App\Models\Collection;
use App\Helpers\Helper;

class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StorePost $form, Thread $thread)
    {
        if ((Auth::user()->admin)||((!$thread->locked)&&(($thread->public)||($thread->user_id==Auth::id())))){
            $post = $form->generatePost($thread);
            $post->checklongcomment();
            event(new NewPost($post));
            if(($thread->book_id>0)&&($post->chapter_id>0)&&($post->chapter->responded<=2)){
                $post->user->reward("first_post");
                return back()->with('success', '您得到了新章节率先回帖的特殊奖励');
            }else{
                $post->user->reward("regular_post");
            }
            return back()->with('success', '您已成功回帖');
        }else{
            return back()->with('danger', '抱歉，本主题锁定或设为隐私，不能回帖');
        }
    }
    public function edit(Post $post)
    {
        $thread=$post->thread;
        $channel=$thread->channel;
        if ((Auth::user()->admin)||((Auth::id() == $post->user_id)&&(!$thread->locked)&&($channel->channel_state!=2))){
            return view('posts.post_edit', compact('post'));
        }else{
            return redirect()->route('error', ['error_code' => '403']);
        }
    }

    public function update(StorePost $form, Post $post)
    {
        $thread=$post->thread;
        $channel=$thread->channel;
        if ((Auth::user()->admin)||((Auth::id() == $post->user_id)&&(!$thread->locked)&&($channel->channel_state!=2))){
            $form->updatePost($post);
            $post->checklongcomment();
            return redirect()->route('thread.showpost', $post->id)->with('success', '您已成功修改帖子');
        }else{
            return redirect()->route('error', ['error_code' => '403']);
        }
    }
    public function show(Post $post)
    {
        $thread = $post->thread;
        $channel = Helper::allChannels()->keyBy('id')->get($thread->channel_id);
        $label = Helper::allLabels()->keyBy('id')->get($thread->label_id);
        $book = $thread->book;
        $post->load('owner','reply_to_post');
        $postcomments = $post->allcomments()->with('owner')->paginate(config('constants.items_per_page'));
        $defaultchapter=$post->chapter_id;
        return view('posts.show',compact('post','thread','postcomments','defaultchapter','book', 'channel','label'))->with('show_as_book',false)->with('chapter_replied',true);
    }

    public function destroy($id){
        $post = Post::findOrFail($id);
        $thread=$post->thread;
        if((!$thread->locked)&&(Auth::id()==$post->user_id)){
            if(($post->maintext)&&($post->chapter_id !=0)){
                $chapter = $post->chapter;
                if($chapter->post_id == $post->id){
                    $chapter->delete();
                }
            }
            $post->delete();
            return redirect()->route('home')->with("success","已经删帖");
        }else{
            return redirect()->route('error', ['error_code' => '403']);
        }
    }
}
