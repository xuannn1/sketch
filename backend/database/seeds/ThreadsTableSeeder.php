<?php

use Illuminate\Database\Seeder;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Vote;

class ThreadsTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $threads = factory(Thread::class)->times(10)->create();
        $threads->each(function ($thread){
            $posts = factory(Post::class)->times(5)->create(['thread_id' => $thread->id]);
            $posts->each(function ($post){
                factory(Vote::class)->times(4)->create(['item_id' => $post->id, 'attitude_type'=>rand(1,4)]);
            });
        });
    }
}
