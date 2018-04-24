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


$statuses = \App\Models\Status::all();
foreach ($statuses as $status){
    $status->content = \Genert\BBCode\Facades\BBCode::convertFromHtml(
        \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($status->content)
    );
    $status->save();
}

\GrahamCampbell\Markdown\Facades\Markdown::convertToHtml('[I\'m an inline-style link](https://www.google.com)');
\Genert\BBCode\Facades\BBCode::convertFromHtml("<p><a href=\"https://www.google.com\">I'm an inline-style link</a></p>\n")


\Http\Helpers\Helper::convertBBCodetoMarkdown($status->content)

$tongrens = \App\Models\Tongren::all();
foreach($tongrens as $tongren){
    if($tongren->tongren_yuanzhu_tag_id>0){
        $tag = \App\Models\Tag::find($tongren->tongren_yuanzhu_tag_id);
        $tongren->tongren_yuanzhu = $tag->tagname.'（'.$tag->tag_explanation.'）';
        $tongren->save();
    }
}

foreach($tongrens as $tongren){
    if($tongren->tongren_CP_tag_id>0){
        $tag = \App\Models\Tag::find($tongren->tongren_CP_tag_id);
        $tongren->tongren_cp=$tag->tagname.'（'.$tag->tag_explanation.'）';
        $tongren->save();
    }
}






$tongren->save();

$tags_feibianyuan = App\Models\Tag::where('tag_group',0);

$tags_feibianyuan->load('threadscount');

$stat_days = [];
for($i=1;$i<20;$i++){
    $data=[];
    $data['qiandaos']=DB::table('users')->where('lastrewarded_at','>',Carbon\Carbon::now()->subday($i)->toDateTimeString())->where('lastrewarded_at','<',Carbon\Carbon::now()->subday($i-1)->toDateTimeString())->count();
    $data['posts']=DB::table('posts')->where('created_at','>',Carbon\Carbon::now()->subday($i)->toDateTimeString())->where('created_at','<',Carbon\Carbon::now()->subday($i-1)->toDateTimeString())->count();
    $data['posts_maintext']=DB::table('posts')->where('created_at','>',Carbon\Carbon::now()->subday($i)->toDateTimeString())->where('created_at','<',Carbon\Carbon::now()->subday($i-1)->toDateTimeString())->where('maintext','=','1')->count();
    $data['posts_reply']=DB::table('posts')->where('created_at','>',Carbon\Carbon::now()->subday($i)->toDateTimeString())->where('created_at','<',Carbon\Carbon::now()->subday($i-1)->toDateTimeString())->where('maintext','=','0')->count();
    $data['post_comments']=DB::table('post_comments')->where('created_at','>',Carbon\Carbon::now()->subday($i)->toDateTimeString())->where('created_at','<',Carbon\Carbon::now()->subday($i-1)->toDateTimeString())->count();
    $data['new_users']=DB::table('users')->where('created_at','>',Carbon\Carbon::now()->subday($i)->toDateTimeString())->where('created_at','<',Carbon\Carbon::now()->subday($i-1)->toDateTimeString())->count();
    $stat_days[$i]=$data;
}
print_r($stat_days);
