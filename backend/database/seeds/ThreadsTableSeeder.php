<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\Post;

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
            $threads->each(function ($thread){
                $posts = factory(Post::class)->times(2)->create(['thread_id' => $thread->id]);
            });
        }
    }
}
