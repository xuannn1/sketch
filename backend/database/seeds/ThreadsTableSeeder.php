<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Chapter;
use App\Models\Volumn;

class ThreadsTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $channels = DB::table('channels')->select('id','is_book')->get();
        foreach($channels as $channel){
            $threads = factory(Thread::class)->times(2)->create([
                'channel_id' => $channel->id,
            ]);
            $threads->each(function ($thread) use ($channel){
                $posts = factory(Post::class)->times(2)->create(['thread_id' => $thread->id]);
                if($channel->is_book){
                    //如果这是一本图书，给他添加示范章节
                    $volumn = factory(Volumn::class)->create();
                    $posts->each(function ($post) use ($volumn){
                        $chapter = factory(Chapter::class)->create([
                            'post_id' => $post->id,
                            'volumn_id' => $volumn->id,
                        ]);
                        $post->is_maintext = true;
                        $post->save();
                    });
                    $posts = factory(Post::class)->times(2)->create(['thread_id' => $thread->id]);
                }
            });

        }
    }
}
