<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;
use ConstantObjects;

trait ThreadObjectTraits{

    public function threadProfile($id)
    {
        return Cache::remember('threadProfile.'.$id, 15, function () use($id){
            $thread = $this->findThread($id);
            $thread->load('tags', 'author.title', 'last_post', 'last_component', 'editor_recommends.post.author');
            if($thread->channel()->type==="list"&&$thread->last_component_id>0&&$thread->last_component){
                $thread->last_component->load('review.reviewee');
            }
            $tongren = \App\Models\Tongren::find($thread->id);
            $thread->setAttribute('tongren', $tongren);
            $thread->setAttribute('recent_rewards', $thread->latest_rewards());
            return $thread;
        });
    }

    public function clearThreadProfile($id)
    {
        Cache::pull('threadProfile.'.$id);
    }

    public function threadProfilePosts($id)
    {
        return Cache::remember('threadProfilePosts.'.$id, 15, function () use($id){
            return \App\Models\Post::with('author.title','last_reply')
            ->withType('post')
            ->where('thread_id','=',$id)
            ->ordered('most_upvoted')
            ->take(5)
            ->get();
        });
    }

    public function findThread($id)
    {
        return Cache::remember('thread.'.$id, 15, function () use($id){
            return \App\Models\Thread::find($id);
        });
    }

    public function clearThread($id)
    {
        Cache::pull('thread.'.$id);
    }

    public function jinghua_threads()
    {
        return Cache::remember('jinghua-threads', 5, function () {
            $jinghua_tag = ConstantObjects::find_tag_by_name('精华');
            return \App\Models\Thread::with('author','tags')
            ->isPublic()
            ->inPublicChannel()
            ->withTag($jinghua_tag->id)
            ->inRandomOrder()
            ->take(3)
            ->get();
        });
    }

    public function find_top_threads_in_channel($id)
    {
        return Cache::remember('top_threads_in_channel.'.$id, 30, function () use($id) {
            $zhiding_tag = ConstantObjects::find_tag_by_name('置顶');
            return \App\Models\Thread::with('author','tags')
            ->inChannel($id)
            ->withTag($zhiding_tag->id)
            ->get();
        });
    }
    public function threadChapterIndex($id)
    {
        return Cache::remember('threadChapterIndex.'.$id, 15, function () use($id) {
            return DB::table('posts')
            ->join('chapters', 'chapters.post_id','=','posts.id')
            ->where('posts.thread_id',$id)
            ->where('posts.type', '=', 'chapter')
            ->orderBy('chapters.order_by','asc')
            ->select('posts.id', 'posts.user_id', 'posts.thread_id', 'posts.title', 'posts.brief', 'posts.created_at', 'posts.edited_at','posts.is_bianyuan', 'posts.char_count', 'posts.view_count', 'posts.reply_count', 'posts.upvote_count', 'chapters.order_by', 'chapters.volumn_id')
            ->get();
        });
    }
    public function clearThreadChapterIndex($id)
    {
        Cache::pull('threadChapterIndex.'.$id);
    }

    public function threadReviewIndex($id)
    {
        return Cache::remember('threadReviewIndex.'.$id, 15, function () use($id) {
            return \App\Models\Post::with('review.reviewee.author')
            ->where('thread_id','=',$id)
            ->withType('review')
            ->get();
        });
    }
    public function clearThreadReviewIndex($id)
    {
        Cache::pull('threadReviewIndex.'.$id);
    }

}
