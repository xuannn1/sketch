<?php

namespace App\Helpers;

use Cache;
use DB;

class ThreadObjects
{
    public static function threadProfile($id)
    {
        return Cache::remember('threadProfile.'.$id, 15, function () use($id){
            $thread = self::thread($id);
            $thread->load('tags', 'author.title', 'last_post', 'last_component','editor_recommends.post.author');
            $thread->setAttribute('temp_rewards', $thread->latest_rewards());
            return $thread;
        });
    }

    public static function threadProfilePosts($id)
    {
        return Cache::remember('threadProfilePosts.'.$id, 15, function () use($id){
            return \App\Models\Post::withType('post')
            ->where('thread_id','=',$id)
            ->ordered('most_upvoted')
            ->take(5)
            ->get();
        });
    }

    public static function thread($id)
    {
        return Cache::remember('thread.'.$id, 15, function () use($id){
            return \App\Models\Thread::find($id);
        });
    }

    public static function post($id)
    {
        return Cache::remember('post.'.$id, 15, function () use($id) {
            $post = \App\Models\Post::find($id);
            if(!$post){
                return;
            }
            $post->load('author.title','tags');
            if($post&&$post->type==='chapter'){
                $post->load('chapter');
            }
            if($post&&$post->type==='review'){
                $post->load('review.reviewee');
            }
            if($post&&$post->type==='question'){
                $post->load('answers');
            }
            if($post&&$post->type==='answer'){
                $post->load('question');
            }
            $post->setAttribute('top_reply', $post->favorite_reply());
            $post->setAttribute('new_reply', $post->newest_reply());

            return $post;
        });
    }

    public static function threadPostsOldest($id, $page=1)
    {
        if($page<=3){//只缓存前三页的结果
            return Cache::remember('threadPosts.'.$id.'P'.$page, 10, function () use($id, $page){
                return self::findPosts($id, $page);
            });
        }
        return self::findPosts($id, $page);
    }

    public static function findPosts($id, $page=1, $orderBy='')
    {
        return \App\Models\Post::where('thread_id',$id)
        ->with('author.title','last_reply')
        ->ordered($orderBy)
        ->paginate(config('preference.posts_per_page'));
    }

    public static function jinghua_threads()
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

    public static function find_primary_tags_in_channel($id)
    {
        return Cache::remember('primary_tags_of_channel.'.$id, 30, function () use($id) {
            $tags = \App\Models\Tag::where('is_primary','=',1)->where('channel_id','=',$id)->get();

            if($id==1){
                $extraTags = \App\Models\Tag::where('is_primary','=',1)->where('channel_id','=',0)->get();
                $tags = $tags->merge($extraTags);
            }
            if($id<=2){
                $extraTags = \App\Models\Tag::where('tag_type','=','性向')->get();
                $tags = $tags->merge($extraTags);
            }
            return $tags;
        });
    }

    public static function find_top_threads_in_channel($id)
    {
        return Cache::remember('top_threads_in_channel.'.$id, 30, function () use($id) {
            $zhiding_tag = ConstantObjects::find_tag_by_name('置顶');
            return \App\Models\Thread::with('author','tags')
            ->inChannel($id)
            ->withTag($zhiding_tag->id)
            ->get();
        });
    }
    public static function threadChapterIndex($id)
    {
        return Cache::remember('threadChapterIndex.'.$id, 15, function () use($id) {
            return DB::table('posts')
            ->join('chapters', 'chapters.post_id','=','posts.id')
            ->where('posts.thread_id',$id)
            ->where('posts.type', '=', 'chapter')
            ->select('posts.id', 'posts.user_id', 'posts.thread_id', 'posts.title', 'posts.brief', 'posts.created_at', 'posts.edited_at','posts.bianyuan', 'posts.char_count', 'posts.view_count', 'posts.reply_count', 'posts.upvote_count', 'chapters.order_by', 'chapters.volumn_id')
            ->get();
        });
    }

}
