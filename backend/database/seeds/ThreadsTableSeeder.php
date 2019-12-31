<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Chapter;
use App\Models\Volumn;
use App\Models\Recommendation;
use App\Models\Review;
use Carbon\Carbon;

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
                    $volumn = factory(Volumn::class)->create([
                        'thread_id' => $thread->id,
                    ]);
                    $posts = factory(Post::class)->times(4)->create(['thread_id' => $thread->id]);
                    $posts->each(function ($post) use ($volumn, $thread){
                        $chapter = factory(Chapter::class)->create([
                            'post_id' => $post->id,
                            'volumn_id' => $volumn->id,
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
                }
                if($channel->type ==='list'){
                    //如果这是文评楼，增加几个文评
                    $posts = factory(Post::class)->times(4)->create(['thread_id' => $thread->id]);
                    $posts->each(function ($post) use ($thread){
                        $review = factory(Review::class)->create([
                            'post_id' => $post->id,
                        ]);
                        $post->type = 'review';
                        $post->save();
                        $thread->add_component_at = Carbon::now();
                        $thread->last_component_id = $post->id;
                        $thread->save();
                    });
                }
                $posts = factory(Post::class)->times(4)->create(['thread_id' => $thread->id]);
                $posts->each(function ($post) use ($thread){
                    $thread->responded_at = Carbon::now();
                    $thread->last_post_id = $post->id;
                    $thread->save();
                });
            });
        }
    }
}
