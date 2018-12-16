<?php

use Illuminate\Database\Seeder;
use App\Models\Channel;
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
        $channels = DB::table('channels')->select('id','channel_state')->get();
        foreach($channels as $channel){
            $labels = DB::table('labels')->where('channel_id',$channel->id)->select('id')->get();
            foreach($labels as $label)
            {
                $threads = factory(Thread::class)->times(1)->create([
                    'channel_id' => $channel->id,
                    'label_id' => $label->id,
                    'thread_group' => $channel->channel_state,
                ]);
                $threads->each(function ($thread){
                    $posts = factory(Post::class)->times(2)->create(['thread_id' => $thread->id]);
                    $posts->each(function ($post){
                        factory(Vote::class)->times(1)->create(['item_id' => $post->id, 'attitude_type'=>rand(1,4)]);
                    });
                    if($thread->channel_id<=2){
                        $tags = DB::table('tags')
                        ->inRandomOrder()
                        ->take(3)
                        ->select('id')
                        ->get();
                        $thread->tags()->sync($tags->pluck('id'));
                        $thread->book_status = rand(1,3);
                        $thread->book_length = rand(1,4);
                        $thread->sexual_orientation = rand(1,7);
                        if (rand(1,2)===2){
                            $thread->is_bianyuan = true;
                            if($thread->thread_group<2){
                                $thread->thread_group = 3;
                            }
                        }
                        if (rand(1,2)===2){
                            $thread->is_anonymous = true;
                            $thread->majia = str_random(5);
                        }
                        $thread->save();
                    }
                });
            }
        }
    }
}
