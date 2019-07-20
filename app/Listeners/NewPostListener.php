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
            // 更新原楼里的信息更改
            $thread->last_post_id = $post->id;
            $thread->responded_at = $post->created_at;

            if($post->type!='post'&&$post->type!='comment'){
                $thread->last_component_id = $post->id;
                if($thread->first_component_id===0){
                    $thread->first_component_id = $post->id;
                }
                if($post->checklongchapter()){
                    $thread->add_component_at = $post->created_at;
                }
                $thread->recalculate_characters();
                $this->clearThreadProfile($thread->id);

                if($post->type==='chapter'){
                    $thread->reorder_chapters();
                    $this->clearThreadChapterIndex($id);
                }
            }else{
                $thread->reply_count+=1;
            }

            $thread->save();

            // 更新被回复对象
            if($post->parent){
                $post->parent->update([
                    'reply_count'=> $post->parent->reply_count+1,
                    'responded_at' => $post->created_at,
                ]);
            }

            //不是给自己的主题顶帖，那么送出跟帖提醒
            if ($post->user_id!=$thread->user_id){
                $post_activity = Activity::create([
                    'kind' => 1,
                    'item_type' => 'post',
                    'item_id' => $post->id,
                    'user_id' => $thread->user_id,
                ]);
                $thread->user->remind('new_reply');
            }

            //如果书籍章节更新，或者非书籍回复，那么告诉不是自己的，所有收藏本讨论串、愿意接受更新的读者, 这里发生了更新
            if(($post->type==='chapter'&&$thread->channel()->type==='book')||($thread->channel()->type!='book')){
                DB::table('collections')
                ->join('user_infos','user_infos.user_id','=','collections.user_id')
                ->join('users','users.id','=','collections.user_id')
                ->where([['collections.thread_id','=',$thread->id],['collections.keep_updated','=',1],['collections.user_id','<>',$post->user_id],['collections.group','=',0]])
                ->update([
                    'collections.updated'=>1,
                    'user_infos.default_collection_updates'=>DB::raw('user_infos.default_collection_updates + 1'),
                    'users.unread_updates' => DB::raw('users.unread_updates + 1'),
                ]);

                DB::table('collections')
                ->join('user_infos','user_infos.user_id','=','collections.user_id')
                ->join('users','users.id','=','collections.user_id')
                ->join('collection_groups','collection_groups.id','=','collections.group')
                ->where([['collections.thread_id','=',$thread->id],['collections.keep_updated','=',1],['collections.user_id','<>',$post->user_id]])
                ->update([
                    'collections.updated'=>1,
                    'collection_groups.update_count'=>DB::raw('collection_groups.update_count + 1'),
                    'users.unread_updates' => DB::raw('users.unread_updates + 1'),
                ]);
            }

            //回帖了，不是给自己的帖子回帖，回帖对象也不是楼主，那么送出回帖提醒
            if(($post->reply_to_id>0)&&($post->user_id!=$post->parent->user_id)&&($post->parent->user_id!=$thread->user_id)){
                $reply_activity = Activity::create([
                    'kind' => 1,
                    'item_type' => 'post',
                    'item_id' => $post->id,
                    'user_id' => $post->parent->user_id,
                ]);
                $post->parent->user->remind('new_reply');
            }

            // 修改惯用马甲，惯用indentation
            $post->user->created_new_post($post);

        });

    }
}
