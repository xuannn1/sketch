<?php

use Illuminate\Database\Seeder;
use App\Thread;
use App\Post;
use App\PostComment;
use App\Channel;
use App\Tag;
use App\Labels;
use App\TaggingThread;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
   {
      $channels = Channel::get();
      $channels->each(function (Channel $channel){
         $label = $channel->labels()->inRandomOrder()->first();
         if (($channel->id >2 )&&($label)) {
            $threads = factory(Thread::class)->times(2)->create(['channel_id' => $channel->id, 'label_id' => $label->id ]);

            $threads->each(function ($thread){
               $posts = factory(Post::class)->times(3)->create(['thread_id' => $thread->id]);

                $posts->each(function ($post){
                  factory(PostComment::class)->times(3)->create(['post_id' => $post->id]);
                });
            });
         }
      });
   }
}
