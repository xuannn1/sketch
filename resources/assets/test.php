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




//去除文章里所有敏感词
$threads = App\Models\Thread::all();
foreach($threads as $thread){
    if($thread->title!=App\Helpers\Helper::convert_to_title($thread->title)){
        $thread->update(['title'=>App\Helpers\Helper::convert_to_title($thread->title)]);
    }
}
$chapters = App\Models\Chapter::all();
foreach($chapters as $chapter){
    if($chapter->title!=App\Helpers\Helper::convert_to_title($chapter->title)){
        $chapter->update(['title'=>App\Helpers\Helper::convert_to_title($chapter->title)]);
    }
}
$posts = App\Models\Post::all();
foreach($posts as $post){
    if($post->title!=App\Helpers\Helper::convert_to_title($post->title)){
        $post->update(['title'=>App\Helpers\Helper::convert_to_title($post->title)]);
    }
}

$users = \App\Models\User::where('lastrewarded_at','>','2018-04-25 00:00:00')->get();
foreach($users as $user){
    if($user->maximum_qiandao == $user->continued_qiandao){
        $date1=Carbon\Carbon::parse($user->lastrewarded_at);
        $date2=Carbon\Carbon::parse($user->created_at);
        $days = $date1->diffInDays($date2);
        if($days+5<$user->continued_qiandao){
            $added_reward_base =0;
            for($i = $days;$i<=$user->continued_qiandao;$i++){
                $reward_base = 1;
                if(($i>=5)&&($i%5==0)){
                    $reward_base = intval($i/10)+2;
                }
                $added_reward_base+=$reward_base;
            }
            echo 'user'.$user->id.'added_base'.$added_reward_base.'|';
        }
    }
}
//increment unread_reminders for each user
$users = App\Models\User::all();
foreach($users as $user){
    $user->increment('unread_reminders');
}
