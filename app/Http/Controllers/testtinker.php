<?php

$allthread = App\Thread::all();
foreach($allthread as $thread){
   $post = App\Post::where('thread_id','=',$thread->id)->oldest()->first();
   $thread->post_id = $post->id;
   $thread->save();
};
