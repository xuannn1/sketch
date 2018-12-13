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
    if($thread->channel_id<=2){
        if($thread->title!=App\Helpers\Helper::convert_to_public($thread->title)){
            $thread->update(['title'=>App\Helpers\Helper::convert_to_public($thread->title)]);
        }
        if($thread->brief!=App\Helpers\Helper::convert_to_public($thread->brief)){
            $thread->update(['brief'=>App\Helpers\Helper::convert_to_public($thread->brief)]);
        }
    }else{
        if($thread->title!=App\Helpers\Helper::convert_to_public($thread->title)){
            $thread->update(['title'=>App\Helpers\Helper::convert_to_public($thread->title)]);
        }
    }
}

$chapters = App\Models\Chapter::all();
foreach($chapters as $chapter){
    if($chapter->title!=App\Helpers\Helper::convert_to_public($chapter->title)){
        $chapter->update(['title'=>App\Helpers\Helper::convert_to_public($chapter->title)]);
    }
}
$posts = App\Models\Post::all();
foreach($posts as $post){
    if($post->title!=App\Helpers\Helper::convert_to_public($post->title)){
        $post->update(['title'=>App\Helpers\Helper::convert_to_public($post->title)]);
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



//manually add this message
$receivers = User::all();
$message_body = 453;
foreach($receivers as $receiver){
    if ($receiver->id>=23135){
        \App\Models\Message::create([
          'message_body' => $message_body,
          'poster_id' => 1,
          'receiver_id' => $receiver->id,
          'private' => false,
        ]);
        $receiver->increment('message_reminders');
        $receiver->increment('unread_reminders');
    }
}


//updating all item_id = thread_id
\App\Models\Collection::where('item_id','=',0)->update(["item_id" => DB::raw("`thread_id`")]);

//updating all thread_id = item_id
\App\Models\Collection::whereNull('thread_id')->update(["thread_id" => DB::raw("`item_id`")]);

//updating all post->body as thread->imap_body
$threads = Thread::all();
foreach ($threads as $thread){
    $post = $thread->mainpost;
    $post->body = $thread->body;
    $thread->body = null;
    $post->save();
    $thread->save();
}

//update last_item_id option in collection_list_store
$collection_lists = App\Models\CollectionList::where('last_item_id','=',0)->get();
foreach($collection_lists as $list){
    $newest_collection = App\Models\Collection::where('collection_list_id','=',$list->id)->orderBy('id','desc')->first();
    if($newest_collection){
        $list->update(['last_item_id'=>$newest_collection->item_id]);
    }
}


//抽出在联文楼下互动的成员：
$posts = App\Models\Post::whereIn('thread_id',[2145, 2148, 2152, 2153, 2163, 2164, 2160])->where('deleted_at','=',null)->where('body','<>',null)->inRandomOrder()->first();

//测试是否成立
App\Helpers\Helper::trimSpaces("   wejr askdj 中文是顶峰。　");

$posts = \App\Models\Post::where('maintext','=',1)->get();
foreach($posts as $post){
    if($post->body !== \App\Helpers\Helper::trimSpaces($post->body)){
        $post->body = \App\Helpers\Helper::trimSpaces($post->body);
        $post->save();
    }
}
//20180728
$lastpost = App\Models\Post::latest()->first();
for($i=1;$i++;$i< $lastpost->id){
    $post = App\Models\Post::find($i);
    if($post){
        $post->trim_body = \App\Helpers\Helper::trimtext($post->body, 50);
        $post->save();
    }
}
//20180729
$lastrecommendedBook = App\Models\RecommendBook::orderby('id','desc')->first();
for($i=1;$i++;$i< $lastrecommendedBook->id){
    $recommend = App\Models\RecommendBook::find($i);
    if($recommend){
        $thread = App\Models\Thread::find($recommend->thread_id);
        if($thread){
            $recommend->update([
                'title' => $thread->title,
                'recommendation' => $thread->brief
            ]);
        }
    }
}


//20180804 怎样快速输出短评信息
$outfile = "";
$recommends = App\Models\RecommendBook::where('long','=',0)->where('past','=',0)->orderby('clicks','desc')->get();
foreach($recommends as $recommend){
    $thread = $recommend->thread;
    $outfile .= "[b]《".$thread->title."》 by ";
    if($thread->anonymous){
        $outfile .= $thread->majia ?? '匿名咸鱼';
    }else{
        $outfile .= $thread->creator->name;
    }
    $outfile .="[/b]\n"."链接：[url]https://sosad.fun/threads/".$recommend->thread_id."[/url]\n"."编推短评：".$recommend->recommendation."\n[br]";
}


//20180804 重新统计每个tag有多少本书信息
$tags = App\Models\Tag::whereNotIn('tag_group', [10, 20])->orderby('id','asc')->get();
foreach($tags as $tag){
    $tag->books = DB::table('tagging_threads')->where('tag_id',$tag->id)->count();
    $tag->save();
}
$tags = App\Models\Tag::where('tag_group','=',10)->orderby('id','asc')->get();
foreach($tags as $tag){
    $tag->books = App\Models\Tongren::where('tongren_yuanzhu_tag_id',$tag->id)->count();
    $tag->save();
}
$tags = App\Models\Tag::where('tag_group','=',20)->orderby('id','asc')->get();
foreach($tags as $tag){
    $tag->books = App\Models\Tongren::where('tongren_CP_tag_id','=',$tag->id)->count();
    $tag->save();
}

//20180810 增加administratee_id列
DB::table('administrations')->join('threads',function($join){ $join->whereIn('administrations.operation',[1,2,3,4,5,6,9,15,16]); $join->on('administrations.item_id','=','threads.id'); })->update(['administrations.administratee_id'=>DB::raw('threads.user_id')]);

DB::table('administrations')->join('posts',function($join){ $join->whereIn('administrations.operation',[7,10,11,12]); $join->on('administrations.item_id','=','posts.id'); })->update(['administrations.administratee_id'=>DB::raw('posts.user_id')]);

DB::table('administrations')->join('post_comments',function($join){ $join->where('administrations.operation','=',8); $join->on('administrations.item_id','=','post_comments.id'); })->update(['administrations.administratee_id'=>DB::raw('post_comments.user_id')]);

DB::table('administrations')->whereIn('administrations.operation',[13,14]) ->update(['administrations.administratee_id'=>DB::raw('administrations.item_id')]);



//去除文章里所有敏感词
$threads = App\Models\Thread::all();
foreach($threads as $thread){
    if($thread->channel_id<=2){
        echo $thread->title."\n";
        if($thread->title!=App\Helpers\Helper::convert_to_title($thread->title)){
            echo $thread->title;
            while($thread->title!=App\Helpers\Helper::convert_to_title($thread->title)){
                $thread->title = App\Helpers\Helper::convert_to_title($thread->title);
            }
            $thread->save();
        }
    }
}
