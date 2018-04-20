<?php

namespace App\Listeners;

use App\Events\NewPost;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;


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
        $thread->update(['last_post_id'=>$post->id]);
        if ($post->chapter_id !=0){//如果是给某个章节回帖，这个章节的数据更新
           $post->chapter->increment('responded');
        }
        if ($post->user_id!=$thread->user_id){//不是给自己的主题顶帖，那么送出跟帖提醒
           $post_activity = Activity::create([
              'type' => 1,
              'item_id' => $post->id,
              'user_id' => $thread->user_id,
           ]);
           $thread->user->increment('post_reminders');
           $thread->user->increment('unread_reminders');
        }

        //如果这不是一本书，那么告诉所有收藏本讨论、愿意接受更新的读者, 这里发生了更新
        if ($thread->book_id==0){
          DB::table('collections')
          ->join('users','users.id','=','collections.user_id')
          ->where([['collections.thread_id','=',$thread->id],['collections.keep_updated','=',true],['collections.user_id','<>',$post->user_id]])
          ->update(['collections.updated'=>1,'users.collection_threads_updated'=>DB::raw('users.collection_threads_updated + 1')]);
         }

         //回帖了，不是给自己的帖子回帖，回帖对象也不是楼主，那么送出回帖提醒
        if(($post->reply_to_post_id!=0)&&($post->user_id!=$post->reply_to_post->user_id)&&($post->reply_to_post->user_id!=$thread->user_id)){
           $reply_activity = Activity::create([
              'type' => 2,
              'item_id' => $post->id,
              'user_id' => $post->reply_to_post->user_id,
           ]);
           $post->reply_to_post->user->increment('reply_reminders');
           $post->reply_to_post->user->increment('unread_reminders');
        }

        //声明本帖已得到回应
        $thread->responded();
        $thread->update_channel();
        $post->user->reward("regular_post");
    }
}
