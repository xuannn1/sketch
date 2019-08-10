<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;
use ConstantObjects;

trait ThreadObjectTraits{
    use FindThreadTrait;

    public function threadProfile($id)
    {
        return Cache::remember('threadProfile.'.$id, 10, function () use($id){
            $thread = $this->findThread($id);
            $thread->load('tags', 'author.title', 'last_post', 'last_component');
            if($thread->channel()->type==="list"&&$thread->last_component_id>0&&$thread->last_component){
                $thread->last_component->load('review.reviewee');
            }
            if($thread->channel()->id===2){
                $tongren = \App\Models\Tongren::find($thread->id);
                $thread->setAttribute('tongren', $tongren);
            }
            if($thread->channel()->type==='book'){
                $thread->setAttribute('chapters', $this->threadChapterIndex($id));
            }
            if($thread->channel()->type==='list'){
                $thread->setAttribute('reviews', $this->threadReviewIndex($id));
            }
            $thread->setAttribute('random_review', $thread->random_editor_recommendation());
            $thread->setAttribute('recent_rewards', $thread->latest_rewards());
            return $thread;
        });
    }

    public function clearThreadProfile($id)
    {
        Cache::forget('threadProfile.'.$id);
    }

    public function threadProfilePosts($id)
    {
        return Cache::remember('threadProfilePosts.'.$id, 10, function () use($id){
            return \App\Models\Post::with('author.title','last_reply')
            ->withType('post')
            ->where('thread_id','=',$id)
            ->where('fold_state','=',0)
            ->ordered('most_upvoted')
            ->take(5)
            ->get();
        });
    }

    public function clearThread($id)
    {
        Cache::forget('thread.'.$id);
    }

    public function clearAllThread($id)
    {
        $this->clearThread($id);
        $this->clearThreadProfile($id);
    }

    public function refreshThread($id)
    {
        $this->clearAllThread($id);
        return $this->threadProfile($id);
    }

    public function threadChapterIndex($id)
    {
        return DB::table('posts')
        ->join('chapters', 'chapters.post_id','=','posts.id')
        ->where('posts.thread_id',$id)
        ->where('posts.deleted_at','=',null)
        ->where('posts.type', '=', 'chapter')
        ->orderBy('chapters.order_by','asc')
        ->select('posts.id', 'chapters.post_id','posts.user_id', 'posts.thread_id', 'posts.title', 'posts.brief', 'posts.created_at', 'posts.edited_at','posts.is_bianyuan', 'posts.char_count', 'posts.view_count', 'posts.reply_count', 'posts.upvote_count', 'chapters.order_by', 'chapters.volumn_id')
        ->get();
    }

    public function threadReviewIndex($id)
    {
        return \App\Models\Post::with('review.reviewee.author')
        ->where('thread_id','=',$id)
        ->withType('review')
        ->get();
    }

    public function decide_thread_show_config($request)
    {
        $show_profile = true;
        $show_selected = false;
        $page = (int)(is_numeric($request->page)? $request->page:'1');
        if($page>1||$request->withType||$request->userOnly||$request->withFolded||$request->withReplyTo||$request->ordered||$request->withComponent){
            $show_profile = false;
        }

        if($request->withType||$request->userOnly||$request->withFolded||$request->withComponent||$request->withReplyTo||$request->ordered){
            $show_selected = true;
        }
        return [
            'show_profile' => $show_profile,
            'show_selected' => $show_selected,
        ];
    }

}
