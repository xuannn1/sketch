<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Chapter;
use App\Models\Volumn;
use App\Models\Recommendation;
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
        $channels = DB::table('channels')->select('id','type')->get();
        foreach($channels as $channel){
            $threads = factory(Thread::class)->times(2)->create([
                'channel_id' => $channel->id,
            ]);
            $threads->each(function ($thread) use ($channel){
                $posts = factory(Post::class)->times(2)->create(['thread_id' => $thread->id]);
                if($channel->type ==='book'){
                    //如果这是一本图书，给他添加示范章节
                    $volumn = factory(Volumn::class)->create();
                    $posts->each(function ($post) use ($volumn, $thread){
                        $chapter = factory(Chapter::class)->create([
                            'post_id' => $post->id,
                            'volumn_id' => $volumn->id,
                        ]);
                        $post->type = 'chapter';
                        $post->save();
                        $thread->last_added_component_at = Carbon::now();
                        $thread->last_component_id = $post->id;
                        $thread->save();
                    });
                    $posts = factory(Post::class)->times(2)->create(['thread_id' => $thread->id]);
                    $recommendation = factory(Recommendation::class)->create([
                        'is_public' => true,
                        'thread_id' => $thread->id,
                    ]);
                    $users = \App\Models\User::inRandomOrder()->take(2)->pluck('id')->toArray();
                    $recommendation->authors()->sync($users);
                }
            });

        }
    }
}
