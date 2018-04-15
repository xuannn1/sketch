<?php

$allthread = App\Models\Thread::all();
foreach($allthread as $thread){
   $post = App\Models\Post::where('thread_id','=',$thread->id)->oldest()->first();
   $thread->post_id = $post->id;
   $thread->save();
};
