<?php
DB::table('users')->where('deleted_at','=',null)->update(['users.experience_points' => DB::raw("jifen")]);

cron command
* * * * * /opt/php71/bin/php /home4/sosad/sketch/artisan schedule:run >> /dev/null 2>&1



$channels = \App\Models\Channel::all();
foreach($channels as $channel){
    $threads = DB::table('threads')->where('channel_id','=',$channel->id)->where('bianyuan','=',0)->where('public','=',1)->orderBy('lastresponded_at','desc')->limit(2)->get();
    $channel->recent_thread_1_id = $threads[0]->id;
    $channel->recent_thread_2_id = $threads[1]->id;
    $channel->save();
}

DB::table('cache')->where('key','like','sosaduser-is-online-%')->count();
