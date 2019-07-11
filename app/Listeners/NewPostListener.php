<?php

namespace App\Listeners;

use App\Events\NewPost;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use App\Models\Activity;
use Cache;

class NewPostListener
//class NewPostListener implements ShouldQueue
{

    //public $queue = 'listeners';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewPost  $event
     * @return void
     */
    public function handle(NewPost $event)
    {
        $post = $event->post;
        $thread = $post->thread;

        DB::transaction(function() use($thread, $post){
            $thread->update([
                'last_post_id'=>$post->id,
                'reply_count'=> $thread->reply_count+1,
                'responded_at' => $post->created_at,
            ]);
            if($post->parent){
                $post->parent->update([
                    'reply_count'=> $post->parent->reply_count+1,
                    'responded_at' => $post->created_at,
                ]);
            }

            if ($post->user_id!=$thread->user_id){//不是给自己的主题顶帖，那么送出跟帖提醒
               $post_activity = Activity::create([
                  'item_type' => 'post',
                  'item_id' => $post->id,
                  'user_id' => $thread->user_id,
               ]);
               $thread->user->info->increment('reply_reminders');
               Cache::pull('cachedUser.'.$thread->user_id);
               $thread->user->increment('unread_reminders');
               Cache::pull('cachedUserInfo.'.$thread->user_id);
            }

            //如果这不是一本书，那么告诉不是自己的，所有收藏本讨论串、愿意接受更新的读者, 这里发生了更新
            if ($thread->channel()->type!='book'){
              DB::table('collections')
              ->join('user_infos','user_infos.user_id','=','collections.user_id')
              ->join('users','users.id','=','collections.user_id')
              ->where([['collections.thread_id','=',$thread->id],['collections.keep_updated','=',1],['collections.user_id','<>',$post->user_id]])
              ->update([
                  'collections.updated'=>1,
                  'user_infos.collection_threads_updates'=>DB::raw('user_infos.collection_threads_updates + 1'),
                  'users.unread_updates' => DB::raw('users.unread_updates + 1'),
              ]);
            }

             //回帖了，不是给自己的帖子回帖，回帖对象也不是楼主，那么送出回帖提醒
            if(($post->reply_to_id>0)&&($post->user_id!=$post->parent->user_id)&&($post->parent->user_id!=$thread->user_id)){
               $reply_activity = Activity::create([
                  'item_type' => 'post',
                  'item_id' => $post->id,
                  'user_id' => $post->parent->user_id,
               ]);
               $post->parent->user->info->increment('reply_reminders');
               Cache::pull('cachedUser.'.$post->parent->user_id);
               $post->parent->user->increment('unread_reminders');
               Cache::pull('cachedUserInfo.'.$post->parent->user_id);
            }
        });

    }
}
