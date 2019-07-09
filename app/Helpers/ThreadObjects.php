<?php

namespace App\Helpers;

use Cache;
use DB;

class ThreadObjects
{
    public static function threadProfile($id)
    {
        return Cache::remember('threadProfile.'.$id, 60, function () use($id){
            $thread = self::thread($id);
            $thread->load('tags', 'author.title', 'last_post', 'last_component');
            $thread->setAttribute('temp_rewards', $thread->latest_rewards());
            return $thread;
        });
    }

    public static function thread($id)
    {
        return Cache::remember('thread.'.$id, 30, function () use($id){
            return \App\Models\Thread::find($id);
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
        ->paginate(config('constants.posts_per_page'));
    }
}
