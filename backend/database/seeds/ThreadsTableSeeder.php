<?php

use Illuminate\Database\Seeder;
use App\Models\Thread;
use App\Models\Post;
use App\Models\PostInfo;
use Carbon\Carbon;
use App\Models\Activity;

class ThreadsTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $channels = collect(config('channel'));
        foreach($channels as $channel){
            $threads = factory(Thread::class)->times(4)->create([
                'channel_id' => $channel->id,
            ]);
            $threads->each(function ($thread) use ($channel){
                if($channel->type ==='book'){
                    //如果这是一本图书，给他添加示范章节
                    $posts = factory(Post::class)->times(4)->create([
                        'thread_id' => $thread->id,
                        'user_id' => $thread->user_id,
                    ]);
                    $posts->each(function ($post) use ($thread){
                        $info = factory(PostInfo::class)->create([
                            'post_id' => $post->id,
                        ]);
                        $post->type = 'chapter';
                        $post->save();
                        $thread->add_component_at = Carbon::now();
                        $thread->last_component_id = $post->id;
                        $thread->save();
                    });
                    $posts = factory(Post::class)->times(2)->create(['thread_id' => $thread->id]);
                    $posts->each(function ($post) use ($thread){
                        $thread->responded_at = Carbon::now();
                        $thread->last_post_id = $post->id;
                        $thread->save();
                    });
                    $posts->each(function($post) use($thread){
                        $activity = factory(Activity::class)->create([
                            'item_id' => $post->id,
                            'item_type' => 'post',
                            'user_id' => $thread->user_id,
                            'kind' => 1,
                        ]);
                    });
                }
                if($channel->type ==='list'){
                    //如果这是一本图书，给他添加示范章节
                    $posts = factory(Post::class)->times(4)->create([
                        'thread_id' => $thread->id,
                        'user_id' => $thread->user_id,
                    ]);
                    $posts->each(function ($post) use ($thread){
                        $info = factory(PostInfo::class)->create([
                            'post_id' => $post->id,
                            'editor_recommend' => rand(0,1),
                        ]);
                        $post->type = 'review';
                        $post->save();
                        $thread->add_component_at = Carbon::now();
                        $thread->last_component_id = $post->id;
                        $thread->save();
                    });
                    $posts = factory(Post::class)->times(2)->create(['thread_id' => $thread->id]);
                    $posts->each(function ($post) use ($thread){
                        $thread->responded_at = Carbon::now();
                        $thread->last_post_id = $post->id;
                        $thread->save();
                    });
                    $posts->each(function($post) use($thread){
                        $activity = factory(Activity::class)->create([
                            'item_id' => $post->id,
                            'item_type' => 'post',
                            'user_id' => $thread->user_id,
                            'kind' => 1,
                        ]);
                    });
                }
            });
        }
    }
}
