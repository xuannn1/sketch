<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Helpers\Helper;
use Cache;
class RebuildDatabase extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'rebuild:database';
    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'update database tables so it fits with new ER-sosad form';
    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        // $this->modifyUserTable();//task 01
        // $this->modifyThreadTable(); // task 02
        // $this->modifyRewardsTable(); // task 03
        $this->modifyPostTable(); // task 04


    }

    public function modifyUserTable()//task 01
    {
        // $this->updateUserInfoNIntro();// task 01.1
        // $this->deleteExtraUserColumns();// task 01.2
        // $this->renameExtraUserColumns();// task 01.2
    }

    public function updateUserInfoNIntro()//task 01.1
    {
        echo "recalculate users data\n";
        DB::table('users')
        ->where('group','>',10)
        ->update(['role'=>'editor']);

        DB::table('users')
        ->where('admin','=',1)
        ->update(['role'=>'admin']);
        DB::table('users')
        ->where('last_quizzed_at', '<>', null)
        ->update([
            'quiz_level'=>1,
        ]);
        DB::table('users')
        ->update([
            'qiandao_at'=>DB::raw('lastrewarded_at'),
        ]);

        DB::table('users')
        ->where('no_posting','>',Carbon::now())
        ->update(['no_posting_or_not'=>true]);

        echo "task 01.1 start modifying users one by one\n";
        \App\Models\User::chunk(1000, function ($users) {
            $insert_intro = [];
            $insert_info = [];
            foreach($users as $user){
                $user_intro = [
                    'user_id' => $user->id,
                    'introduction' => $user->introduction,
                    'updated_at' => Carbon::now(),
                ];

                $user_info = [
                    'user_id' => $user->id,
                    'shengfan' => $user->shengfan,
                    'xianyu' => $user->xianyu,
                    'jifen' => $user->jifen,
                    'sangdian' => $user->sangdian,
                    'exp' => $user->experience_points,
                    'upvotes' => $user->upvoted,
                    'brief_intro' => \App\Helpers\Helper::trimtext($user->introduction, 20),
                    'majia' => $user->majia,
                    'indentation' =>$user->indentation,
                    'activation_token' => $user->activation_token,
                    'invitation_token' => $user->invitation_token,
                    'no_posting_until' => $user->no_posting,
                    'no_logging_until' => $user->no_logging,
                    'continued_qiandao' => $user->continued_qiandao,
                    'max_qiandao' =>$user->maximum_qiandao,
                    'no_stranger_msg' => !$user->receive_messages_from_strangers,
                    'no_upvote_reminders' => $user->no_upvote_reminders,
                    'clicks' =>  $user->clicks,
                    'daily_clicks' =>  $user->daily_clicks,
                    'reply_reminders' =>  $user->reply_reminders+$user->post_reminders+$user->postcomment_reminders+$user->system_reminders,
                    'upvote_reminders' => $user->upvote_reminders,
                    'message_reminders' => $user->message_reminders,
                    'public_notices' => $user->public_notices,
                    'collection_threads_updates' => $user->collection_threads_updated,
                    'collection_books_updates' => $user->collection_books_updated,
                    'collection_statuses_updates' => $user->collection_statuses_updated,
                    'login_ip' => $user->last_login_ip,
                    'login_at' => $user->last_login,
                    'created_at' => $user->created_at,
                ];
                array_push($insert_info, $user_info);
                if($user_intro['introduction']){
                    array_push($insert_intro, $user_intro);
                }
            }
            DB::table('user_intros')->insert($insert_intro);
            DB::table('user_infos')->insert($insert_info);
            echo $user->id."|";
        });
    }

    public function deleteExtraUserColumns()
    {
        echo "task 1.2.1 delete extra users table\n";
        Schema::table('users', function($table){
            $table->dropColumn(['activation_token', 'created_at', 'updated_at', 'shengfan', 'xianyu', 'jifen', 'upvoted', 'downvoted', 'lastresponded_at', 'introduction', 'viewed', 'invitation_token', 'last_login_ip', 'last_login', 'admin', 'superadmin', 'group', 'no_posting', 'no_logging', 'lastrewarded_at', 'sangdian', 'guarden_deadline', 'continued_qiandao', 'post_reminders', 'postcomment_reminders', 'reply_reminders', 'replycomment_reminders', 'message_reminders', 'collection_threads_updated', 'collection_books_updated', 'collection_statuses_updated', 'majia','message_limit', 'receive_messages_from_stranger', 'no_registration', 'upvote_reminders', 'no_upvote_reminders', 'total_char', 'experience_points', 'lastsearched_at', 'maximum_qiandao', 'indentation', 'system_reminders', 'collection_lists_updated', 'collection_list_limit', 'clicks', 'daily_clicks', 'daily_posts', 'daily_chapters', 'daily_characters', 'last_quizzed_at', 'quizzed']);
            echo "echo deleted extra users columns.\n";
        });
    }

    public function renameExtraUserColumns()
    {
        echo "task 1.2.2 rename users table\n";
        Schema::table('users', function($table){
                $table->renameColumn('user_level', 'level');
                $table->renameColumn('no_logging_or_not', 'no_logging');
                $table->renameColumn('no_posting_or_not', 'no_posting');
                echo "echo renamed users table columns.\n";
        });
    }

    public function modifyThreadTable()//task 02
    {

        // $this->modifyTagThreadTable();//task 2.1
        // $this->modifyTagTable();//task 2.2
        //  $this->updateThreadTable();//task 2.3
        // $this->insertThreadTable();//task 2.4
        // $this->modifyTongrenTable();//task 2.5
    }

    public function modifyTagThreadTable() //task 2.1
    {
        echo "start task2.1 modifyTagThreadTable\n";
        if(Schema::hasTable('tagging_threads')){
            Schema::rename('tagging_threads', 'tag_thread');
            echo "renamed tag_thread table\n";
        }
        if(Schema::hasTable('tag_thread')){
            if (Schema::hasColumn('tag_thread', 'id')){
                Schema::table('tag_thread', function($table){
                    $table->dropColumn(['created_at', 'updated_at']);
                });
                echo "dropped old tag_thread columns\n";
            }
        }
    }

    public function modifyTagTable() //task 2.2
    {
        echo "start task2.2 modifyTagTable\n";
        if(!Schema::hasColumn('tags', 'tag_type')){
            Schema::table('tags', function($table){
                $table->string('tag_type', 10);
                $table->boolean('is_bianyuan')->default(false);
                $table->boolean('is_primary')->default(false);
                $table->unsignedInteger('channel_id')->default(0);//是否某个channel专属
                echo "echo added new columns to tags table.\n";
            });
            Schema::table('tags', function($table){
                $table->renameColumn('tag_belongs_to', 'parent_id');
                $table->renameColumn('books', 'book_count');
                $table->renameColumn('tagname', 'tag_name');
                $table->unique('tag_name');
                echo "echo renamed tags table columns.\n";
            });
        }
        if(Schema::hasColumn('tags', 'tag_group')){
            echo "start modify existing tags.\n";
            $tags = \App\Models\Tag::all();
            foreach($tags as $tag){
                if($tag->tag_info>0){
                    $tag->tag_type = config('constants.tag_info')[$tag->tag_info];
                }
                if($tag->tag_group===5){
                    $tag->is_bianyuan = true;
                }
                if($tag->tag_group===10){
                    $tag->tag_type = '同人原著';
                    $tag->channel_id = 2;
                }
                if($tag->tag_group===20){
                    $tag->tag_type = '同人CP';
                    $tag->channel_id = 2;
                }
                if($tag->tag_group===25){
                    $tag->tag_type = '同人聚类';
                    $tag->channel_id = 2;
                }
                if($tag->label_id>0&&$tag->tag_group===0){
                    $tag->is_primary = true;
                }
                $tag->save();
            }
            echo "end modify existing tags.\n";
        }
        echo "start insert more tags.\n";
        $labels = \App\Models\Label::all();
        foreach($labels as $label){
            $target_tag = \App\Models\Tag::where('tag_name',$label->labelname)->first();
            if(!$target_tag){
                DB::table('tags')->insert([
                    'tag_name' => $label->labelname,
                    'channel_id' => $label->channel_id,
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
            }
        }

        {
            DB::table('tags')->insert([
                'tag_name' => '短篇',
                'tag_type' => '篇幅',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '中篇',
                'tag_type' => '篇幅',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '长篇',
                'tag_type' => '篇幅',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '大纲',
                'tag_type' => '篇幅',
            ]);
        }
        {
            DB::table('tags')->insert([
                'tag_name' => '连载',
                'tag_type' => '进度',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '完结',
                'tag_type' => '进度',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '暂停',
                'tag_type' => '进度',
            ]);
        }
        {
            DB::table('tags')->insert([
                'tag_name' => 'BL',
                'tag_type' => '性向',
            ]);
            DB::table('tags')->insert([
                'tag_name' => 'GL',
                'tag_type' => '性向',
            ]);
            DB::table('tags')->insert([
                'tag_name' => 'BG',
                'tag_type' => '性向',
            ]);
            DB::table('tags')->insert([
                'tag_name' => 'GB',
                'tag_type' => '性向',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '混合性向',
                'tag_type' => '性向',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '无CP',
                'tag_type' => '性向',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '其他性向',
                'tag_type' => '性向',
            ]);
        }
        {

            DB::table('tags')->insert([
                'tag_name' => '荤素均衡',
                'is_bianyuan' => false,
                'tag_type' => '床戏性质'
            ]);
            DB::table('tags')->insert([
                'tag_name' => '肉渣',
                'is_bianyuan' => false,
                'tag_type' => '床戏性质'
            ]);

        }
        {
            DB::table('tags')->insert([
                'tag_name' => '专题推荐',
                'tag_type' => '编推',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '当前编推',
                'tag_type' => '编推',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '往期编推',
                'tag_type' => '编推',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '高亮',
                'tag_type' => '管理',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '置顶',
                'tag_type' => '管理',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '精华',
                'tag_type' => '管理',
            ]);
        }
        {
            DB::table('tags')->insert([
                'tag_name' => '想读',
                'tag_type' => '阅读进度',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '正在读',
                'tag_type' => '阅读进度',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '养肥',
                'tag_type' => '阅读进度',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '弃文',
                'tag_type' => '阅读进度',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '已读',
                'tag_type' => '阅读进度',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '多次阅读',
                'tag_type' => '阅读反馈',
            ]);
            DB::table('tags')->insert([
                'tag_name' => '推荐',
                'tag_type' => '阅读反馈',
            ]);
        }

        echo "end insert more tags.\n";
        echo "start modify tags into new formats\n";
        if(Schema::hasColumn('tags', 'label_id')){
            $tags = \App\Models\Tag::where('label_id','>', 0)->get();
            foreach($tags as $tag)
            {
                $label = \App\Models\Label::where('id','=', $tag->label_id)->first();
                if($label){
                    $label_tag = \App\Models\Tag::where('tag_name','=', $label->labelname)->first();
                    if($label_tag){
                        $tag->parent_id = $label_tag->id;
                        $tag->save();
                    }else{
                        echo "cannot find new_label_tag->label_id=".$label->id."new_label_tag->tag_name=".$label->labelname."\n";
                    }
                }else{
                    echo "cannot find old_label->label_id=".$tag->label_id."\n";
                }
            }
        }
        if(Schema::hasColumn('tags', 'tag_group')){
            Schema::table('tags', function($table){
                $table->dropColumn(['tag_group','lastresponded_at','label_id','tag_info']);
            });
            echo "dropped old columns\n";
        }
        echo "finished modify tags into new forms\n";
    }

    public function updateThreadTable() //task 2.3
    {
        echo "start task2.3 updateThreadTable\n";
        if(!Schema::hasColumn('threads', 'total_char')){
            Schema::table('threads', function($table){
                $table->unsignedInteger('last_component_id')->default(0);
                $table->dateTime('add_component_at')->nullable();
                $table->string('creation_ip', 45)->nullable();//创建时IP地址
                $table->boolean('markdown')->default(false);
                $table->boolean('indentation')->default(true);
                $table->unsignedInteger('weighted_jifen')->default(0);
                $table->unsignedInteger('total_char')->default(0);
                echo "added new columns to threads table.\n";
            });
        }
        if(!Schema::hasColumn('threads', 'body')){
            Schema::table('threads', function($table){
                $table->renameColumn('delete_body', 'body');
                $table->renameColumn('viewed', 'view_count');
                $table->renameColumn('responded', 'reply_count');
                $table->renameColumn('lastresponded_at', 'responded_at');
                $table->renameColumn('collection', 'collection_count');
                $table->renameColumn('noreply', 'no_reply');
                $table->renameColumn('downloaded', 'download_count');
                echo "echo renamed threads columns.\n";
            });
        }
    }

    public function insertThreadTable() //task 2.4
    {
        echo "start task2.4 modifyThreadTable\n";
        echo "start modify threads table body and other data\n";
        DB::table('threads')
        ->join('posts','posts.id','=','threads.post_id')
        ->update([
            'threads.body' => DB::raw('posts.body'),
            'threads.creation_ip' => DB::raw('posts.user_ip'),
            'threads.markdown' => DB::raw('posts.markdown'),
            'threads.indentation' => DB::raw('posts.indentation')
        ]);

        DB::table('threads')
        ->join('books','books.id','=','threads.book_id')
        ->where('books.deleted_at','=',null)
        ->update([
            'threads.weighted_jifen' => DB::raw('books.weighted_jifen'),
            'threads.total_char' => DB::raw('books.total_char'),
        ]);

        DB::table('threads')
        ->join('books','books.id','=','threads.book_id')
        ->join('chapters','books.last_chapter_id','=','chapters.id')
        ->where('books.deleted_at','=',null)
        ->where('chapters.deleted_at','=',null)
        ->update([
            'threads.last_component_id' => DB::raw('books.last_chapter_id'),
            'threads.add_component_at' => DB::raw('books.lastaddedchapter_at'),
        ]);

        echo "start sync threads old tags\n";

        Cache::put('allTags', \App\Models\Tag::all(), 10);
        Cache::put('allLabels', \App\Models\Label::all(), 10);

        \App\Models\Thread::with('book.tongren')->chunk(50, function ($threads) {
            foreach ($threads as $thread) {
                $insert_tags = [];
                if($thread->book_id>0){
                    $book = $thread->book;
                    if($book->id>0){
                        if($book->book_length>0){
                            $tag = $this->findTagByName(config('constants.book_info.book_length_info')[$book->book_length]);
                            if($tag){array_push($insert_tags,['tag_id'=>$tag->id,'thread_id'=>$thread->id]);}
                        }
                        if($book->book_status>0){
                            $tag = $this->findTagByName(config('constants.book_info.book_status_info')[$book->book_status]);
                            if($tag){array_push($insert_tags,['tag_id'=>$tag->id,'thread_id'=>$thread->id]);}
                        }
                        if($book->sexual_orientation>0){
                            $tag = $this->findTagByName(config('constants.book_info.sexual_orientation_info')[$book->sexual_orientation]);
                            if($tag){array_push($insert_tags,['tag_id'=>$tag->id,'thread_id'=>$thread->id]);}
                        }
                        $tongren = $book->tongren;
                        if($tongren->id>0){
                            if($tongren->tongren_yuanzhu_tag_id){
                                array_push($insert_tags,['tag_id'=>$tongren->tongren_yuanzhu_tag_id,'thread_id'=>$thread->id]);
                            }
                            if($tongren->tongren_CP_tag_id){
                                array_push($insert_tags,['tag_id'=>$tongren->tongren_CP_tag_id,'thread_id'=>$thread->id]);
                            }
                        }
                    }
                }
                if($thread->top){
                    $tag = $this->findTagByName('置顶');
                    if($tag){array_push($insert_tags,['tag_id'=>$tag->id,'thread_id'=>$thread->id]);}
                }
                if($thread->jinghua){
                    $tag = $this->findTagByName('精华');
                    if($tag){array_push($insert_tags,['tag_id'=>$tag->id,'thread_id'=>$thread->id]);}
                }
                if($thread->recommended){
                    $tag = $this->findTagByName('当前编推');
                    if($tag){array_push($insert_tags,['tag_id'=>$tag->id,'thread_id'=>$thread->id]);}
                }
                $label_tag = $this->findTagByLabelId($thread->label_id);
                if($label_tag){
                    array_push($insert_tags,['tag_id'=>$label_tag->id,'thread_id'=>$thread->id]);
                }else{
                    echo "when syncing tags to threads, cannot find old_label->label_id=".$thread->label_id."\n";
                }
            }
            DB::table('tag_thread')
            ->insert($insert_tags);
            echo $thread->id."|";
        });

        echo "remove duplicates\n";
        DB::statement('
            DELETE t1 FROM tag_thread t1
            INNER JOIN
            tag_thread t2
            WHERE
            t1.id < t2.id AND t1.tag_id = t2.tag_id and t1.thread_id = t2.thread_id;
        ');

    }

    public function findTagByName($tagname)
    {
        $tag = Helper::alltags()->keyBy('tag_name')->get($tagname);
        // $tag = \App\Models\Tag::where('tag_name','=', $tagname)->first();
        if(!$tag){
            echo "cannot find tag=".$tagname."|";
        }
        return $tag;
    }
    public function findTagByLabelId($label_id)
    {
        $label_tag = null;
        $label = Helper::allLabels()->keyBy('id')->get($label_id);
        if($label){
            $label_tag = Helper::alltags()->keyBy('tag_name')->get($label->labelname);
            if(!$label_tag){
                echo "cannot find label_tag:label_id=".$label->id."label_name=".$label->labelname."\n";
            }
        }else{
            echo "cannot find old_label->label_id=".$label_id."\n";
        }
        return $label_tag;
    }

    public function modifyTongrenTable() //task 2.5
    {
        echo "start task2.5 modifyTongrenTable\n";
        if(!Schema::hasColumn('tongrens', 'thread_id')){
            Schema::table('tongrens', function($table){
                $table->unsignedInteger('thread_id')->index();
            });
            echo "added thread_id column to tongrens\n";
        }
        if(Schema::hasColumn('tongrens', 'tongren_yuanzhu_tag_id')){
            \App\Models\Tongren::with('book.thread')->chunk(200, function ($tongrens) {
                foreach($tongrens as $tongren){
                    $book = $tongren->book;
                    $thread = $book->thread;
                    if($book&&$thread){
                        $tongren->thread_id = $thread->id;
                        if($tongren->tongren_yuanzhu_tag_id>0){
                            $tag = \App\Models\Tag::find($tongren->tongren_yuanzhu_tag_id);
                            if($tag){
                                $tongren->tongren_yuanzhu = null;
                            }else{
                                echo "this tongren_yuanzhu_id cannot find tag:".$tongren->tongren_yuanzhu_tag_id."\n";
                            }
                        }
                        if($tongren->tongren_CP_tag_id>0){
                            $tag = \App\Models\Tag::find($tongren->tongren_CP_tag_id);
                            if($tag){
                                $tongren->tongren_cp = null;
                            }else{
                                echo "this tongren_cp_id cannot find tag:".$tongren->tongren_CP_tag_id."\n";
                            }
                        }
                        $tongren->save();
                    }else{
                        echo "this tongren has problem find book tongren_id=".$tongren->id."\n";
                    }
                    if($tongren->tongren_yuanzhu_tag_id>0&&$tongren->tongren_CP_tag_id>0){
                        $tongren->delete();
                    }
                    if($tongren->thread_id===0){
                        $tongren->delete();
                    }
                    if($tongren->tongren_yuanzhu===null&&$tongren->tongren_cp===null){
                        $tongren->delete();
                    }
                }
                echo $tongren->id.'|';
            });
        }
        DB::table('tongrens')->where('thread_id', '=', 0)->delete();
        DB::table('tongrens')->where('tongren_yuanzhu', '=', null)->where('tongren_cp', '=', null)->delete();
        if(Schema::hasColumn('tongrens', 'book_id')){
            Schema::table('tongrens', function($table){
                $table->dropColumn(['book_id','tongren_CP_tag_id', 'tongren_yuanzhu_tag_id', 'deleted_at','created_at','updated_at']);
            });
            echo "dropped tongrens table extra columns\n";
        }
    }

    public function modifyRewardsTable() //task 3
    {
        echo "started task 3 modify rewards table\n";
        if (!Schema::hasTable('rewards')) {
            Schema::create('rewards', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->index();
                $table->string('rewardable_type',10)->nullable()->index();
                $table->unsignedInteger('rewardable_id')->default(0)->index();
                $table->string('reward_type',10)->nullable()->index();
                $table->integer('reward_value')->default(0);//也有可能是负数
                $table->dateTime('created_at')->nullable();
            });
            echo "created rewards table\n";
        }
        echo "started storing shengfans to rewards\n";
        \App\Models\Shengfan::with('post')->chunk(1000, function ($shengfans) {
            $insert_shengfan = [];
            foreach($shengfans as $shengfan){
                $post = $shengfan->post;
                if($post->id>0&&$post->thread_id>0&&$post->user_id>0){
                    $shengfan_data = [
                        'user_id' => $shengfan->user_id,
                        'rewardable_type' => 'thread',
                        'rewardable_id' => $post->thread_id,
                        'reward_type' => 'shengfan',
                        'reward_value' => $shengfan->shengfan_num,
                        'created_at' =>$shengfan->created_at,
                    ];
                    array_push($insert_shengfan, $shengfan_data);
                }
            }
            DB::table('rewards')->insert($insert_shengfan);
            echo $shengfan->id."|";
        });
        echo "finished storing shengfans\n";
        echo "start storing xianyus\n";
        \App\Models\Xianyu::with('thread')->chunk(1000, function ($xianyus) {
            $insert_xianyu = [];
            foreach($xianyus as $xianyu){
                if($xianyu->thread_id>0){
                    $xianyu_data = [
                        'user_id' => $xianyu->user_id,
                        'rewardable_type' => 'thread',
                        'rewardable_id' => $xianyu->thread_id,
                        'reward_type' => 'xianyu',
                        'reward_value' => 1,
                        'created_at' =>$xianyu->created_at,
                    ];
                    array_push($insert_xianyu, $xianyu_data);
                }
            }
            DB::table('rewards')->insert($insert_xianyu);
            echo $xianyu->id."|";
        });
        echo "finished storing shengfans\n";
    }



    public function modifyPostTable()
    {
        echo "start task4 modifyPostTable\n";
        $this->modifyPostTableColumns();//tas 4.1
        $this->modifyChapterTableColumns();//task 4.2
        $this->moveChapterToPost();//task 4.3
        $this->updatePostTypeColumn();//task 4.4
        $this->modifyPostCommentTable();//task 4.5
        $this->movePostCommentToPost();//task 4.6
    }
    public function modifyPostTableColumns()
    {
        echo "start task4.1 modifyPostTableColumns\n";
        if(!Schema::hasColumn('posts', 'type')){
            Schema::table('posts', function($table){
                $table->string('type',10)->nullable();
                $table->string('reply_to_brief')->nullable();
                $table->unsignedInteger('reply_to_position')->default(0);
                $table->unsignedInteger('new_reply_id')->default(0);
                $table->unsignedInteger('reply_count')->default(0);
                $table->unsignedInteger('view_count')->default(0);
                $table->unsignedInteger('char_count')->default(0);
                echo "added new post columns.\n";
            });
        }
        if(!Schema::hasColumn('posts', 'upvote_count')){
            Schema::table('posts', function($table){
                $table->renameColumn('trim_body', 'brief');
                $table->renameColumn('up_voted', 'upvote_count');
                $table->renameColumn('fold_state', 'is_folded');
                $table->renameColumn('lastresponded_at', 'responded_at');
                $table->renameColumn('reply_to_post_id', 'reply_to_id');
                $table->renameColumn('user_ip', 'creation_ip');
                $table->index('reply_id');//增加一个index
                echo "renamed post columns.\n";
            });
        }
    }
    public function modifyChapterTableColumns()
    {
        echo "start task4.2 modifyChapterTableColumns\n";
        if(!Schema::hasColumn('chapters', 'next_id')){
            Schema::table('chapters', function($table){
                $table->text('warning')->nullable();
                $table->unsignedInteger('next_id')->default(0);
                $table->unsignedInteger('previous_id')->default(0);
                echo "added new chapter columns.\n";
            });
        }
        if(!Schema::hasColumn('chapters', 'order_by')){
            Schema::table('chapters', function($table){
                $table->renameColumn('chapter_order', 'order_by');
                echo "renamed chapter columns.\n";
            });
        }
    }
    public function moveChapterToPost()
    {
        echo "start task4.3 moveChapterToPost\n";
        \App\Models\Chapter::with('mainpost')->chunk(1000, function ($chapters) {
            foreach ($chapters as $chapter) {
                $post = $chapter->mainpost;
                if($post->id>0){
                    if($post->maintext){
                        $post->type='chapter';
                    }
                    $post->view_count = $chapter->viewed;
                    $post->brief = $post->title;
                    $post->title = $chapter->title;
                    $post->edited_at = $chapter->edited_at;
                    $post->save();
                }
            }
            echo $post->id."|";
        });
        echo "finished task5.3 moveChapterToPost\n";
    }
    public function updatePostTypeColumn()
    {
        echo "start task5.4 updatePostTypeColumn\n";
        DB::table('posts')
        ->where('maintext','=',0)
        ->where('reply_to_id','>',0)
        ->update(['type'=>'comment']);
        echo "updated comment type.\n";
        DB::table('posts')
        ->join('chapters','chapters.post_id','=','posts.chapter_id')
        ->where('posts.maintext','=',0)
        ->where('posts.reply_to_id','=',0)
        ->update(['reply_to_id'=>DB::raw('')]);

        \App\Models\Post::chunk(1000, function ($posts) {
            foreach ($posts as $post) {
                if(!$post->maintext){
                    if($post->reply_id>0){
                        $post->type='comment';
                    }else{
                        $post->type='post';
                        if($post->chapter_id>0){
                            $post->reply_id = $post->chapter->post_id;
                        }
                    }
                    $post->save();
                }
            }
            echo $post->id."|";
        });
        echo "finished task5.4 updatePostTypeColumn\n";
    }
    public function modifyPostCommentTable()
    {
        echo "start task5.5 modifyPostCommentTable\n";
        if(!Schema::hasColumn('post_comments', 'new_post_id')){
            Schema::table('post_comments', function($table){
                $table->unsignedInteger('new_post_id')->default(0);
                echo "added new postcomment columns.\n";
            });
        }
    }
    public function movePostCommentToPost()
    {
        echo "start task5.6 movePostCommentToPost\n";
        \App\Models\PostComment::chunk(1000, function ($postcomments) {
            foreach ($postcomments as $postcomment) {
                $post = $postcomment->post;
                $thread = $post->thread;
                if($post->id>0&&$thread->id>0){
                    $post_data=[];
                    $post_data['body'] = $postcomment->body;
                    $post_data['majia'] = $postcomment->majia;
                    $post_data['is_anonymous'] = $postcomment->anonymous;
                    $post_data['created_at'] = $postcomment->created_at;
                    $post_data['thread_id'] = $post->thread_id;
                    if($post->maintext){
                        $post_data['type']='post';
                        $post_data['reply_id']=$post->id;
                    }elseif($post->id===$thread->post_id){
                        $post_data['type']='post';
                    }else{
                        $post_data['type']='comment';
                        $post_data['reply_id']=$post->id;
                    }
                    $new_post = \App\Models\Post::create($post_data);
                    $postcomment->new_post_id = $new_post->id;
                    $postcomment->save();
                }
            }
            echo $new_post->id."|";
        });
        echo "finished task5.6 movePostCommentToPost\n";
    }
}
