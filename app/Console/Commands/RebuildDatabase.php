<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
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
        $this->modifyRewardsTable();//task 0
        $this->modifyTagThreadTable();//task 1
        $this->modifyTagTable();//task 2
        $this->updateThreadTable();//task 3.1
        $this->modifyThreadTable();//task 3.2
        $this->modifyTongrenTable();//task 4
        $this->modifyPostTable();//task 5
        $this->updatePostReply();//task 6
        $this->removeMainPost();//task 7
        $this->cleanupPostThreadChapterVolumn();//task 8
        $this->removeBookPostCommentTable();//task 9
        $this->removeDeletedThreadPost();//task 10
        $this->removeExtraTotalTables();//task 11
        $this->moveRecommendationToReview();//task 12
        $this->moveCollectionListToReview();//task 13
        $this->updateCollectionsTable();//task 14
        $this->moveQuestionToBox();//task 15
        $this->updateQuotes();//task 16
        $this->updateStatuses();//task 17
        $this->updateVotesTable(); //task 18
        $this->finalWrapUpTables();//task 19
        $this->remakeSystemsTables();//task 20
        $this->modifyUserTable();//task 21
    }
    public function modifyRewardsTable()
    {
        echo "started task 0 modify rewards table\n";
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
            foreach($shengfans as $shengfan){
                $post = $shengfan->post;
                if($post->id>0){
                    $thread = \App\Models\Thread::find($post->thread_id);
                    if($thread&&$thread->post_id===$post->id){
                        DB::table('rewards')->insert([
                            'user_id' => $shengfan->user_id,
                            'rewardable_type' => 'thread',
                            'rewardable_id' => $thread->id,
                            'reward_type' => 'shengfan',
                            'reward_value' => $shengfan->shengfan_num,
                            'created_at' =>$shengfan->created_at,
                        ]);
                    }
                }
            }
            echo $shengfan->id."|";
        });
        echo "finished storing shengfans\n";
        echo "start storing xianyus\n";
        \App\Models\Xianyu::chunk(1000, function ($xianyus) {
            foreach($xianyus as $xianyu){
                $thread = \App\Models\Thread::find($xianyu->thread_id);
                if($thread){
                    DB::table('rewards')->insert([
                        'user_id' => $xianyu->user_id,
                        'rewardable_type' => 'thread',
                        'rewardable_id' => $thread->id,
                        'reward_type' => 'xianyu',
                        'reward_value' => 1,
                        'created_at' =>$xianyu->created_at,
                    ]);
                }
            }
            echo $xianyu->id."|";
        });
        echo "finished storing shengfans\n";
    }
    public function modifyTagThreadTable()
    {
        echo "start task1 modifyTagThreadTable\n";
        if(Schema::hasTable('tagging_threads')){
            Schema::rename('tagging_threads', 'tag_thread');
            echo "renamed tag_thread table\n";
        }
        if(Schema::hasTable('tag_thread')){
            if (Schema::hasColumn('tag_thread', 'id')){
                Schema::table('tag_thread', function($table){
                    $table->dropColumn(['id', 'created_at', 'updated_at']);
                });
                echo "dropped old tag_thread columns\n";
            }
        }
        if(Schema::hasTable('tag_thread')){
            Schema::table('tag_thread', function($table){
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesFound = $sm->listTableIndexes('tag_thread');
                //dd($indexesFound);

                if(array_key_exists("tagging_threads_thread_id_index", $indexesFound)){
                    $table->dropIndex('tagging_threads_thread_id_index');
                }

                if(array_key_exists("tagging_threads_tag_id_index", $indexesFound)){
                    $table->dropIndex('tagging_threads_tag_id_index');
                }

                if(!array_key_exists("primary", $indexesFound)){
                    $table->primary(['tag_id','thread_id']);
                    $table->index('thread_id');
                }
            });
            echo "modified old tag_thread indexes\n";
        }
    }
    public function modifyTagTable()
    {
        echo "start task2 modifyTagTable\n";
        if(!Schema::hasColumn('tags', 'tag_type')){
            Schema::table('tags', function($table){
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesFound = $sm->listTableIndexes('tags');
                if(array_key_exists("tags_lastresponded_at_index", $indexesFound)){
                    $table->dropIndex('tags_lastresponded_at_index');
                    echo "removed old tag indexes.\n";
                }
            });
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

        $tag = \App\Models\Tag::where('tag_name','影视')->first();
        if(!$tag){
            echo "start insert more tags.\n";
            {//大类标签
                DB::table('tags')->insert([
                    'tag_name' => '其他原创',
                    'channel_id' => '1',
                    'tag_type' => '大类',
                    'is_primary' => true,
                ]);
                {//（同人大类 Channel No.2）影视、动漫、游戏、小说、真人、其他
                    DB::table('tags')->insert([
                        'tag_name' => '影视',
                        'channel_id' => '2',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '动漫',
                        'channel_id' => '2',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '游戏',
                        'channel_id' => '2',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '小说',
                        'channel_id' => '2',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '真人',
                        'channel_id' => '2',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '历史',
                        'channel_id' => '2',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '戏剧',
                        'channel_id' => '2',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '其他同人',
                        'channel_id' => '2',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//（作业 Channel No.3）作业专区
                    DB::table('tags')->insert([
                        'tag_name' => '本次作业',
                        'channel_id' => '3',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '往期作业',
                        'channel_id' => '3',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '其他作业',
                        'channel_id' => '3',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//（板块：读写交流 Channel No.4） 分享、探讨、评文、自荐、推文
                    DB::table('tags')->insert([
                        'tag_name' => '技法探讨',
                        'channel_id' => '4',
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '写作探讨',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '评文推文',
                        'channel_id' => '4',
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '我有一句…不吐不快',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '自荐求评',
                        'channel_id' => '4',
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '鼓足勇气的第一步',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '读书记录',
                        'channel_id' => '4',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '读写活动',
                        'channel_id' => '4',
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '来自作业区的问候',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '写手调查',
                        'channel_id' => '4',
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '全面测试写手素质',
                    ]);
                }
                {//（板块：日常闲聊 Channel No.5） 闲谈、吐槽、求助、八卦、安利
                    DB::table('tags')->insert([
                        'tag_name' => '闲谈',
                        'channel_id' => '5',//日常闲聊
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '闲谈',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '吐槽',
                        'channel_id' => '5',//日常闲聊
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '吐吐更健康',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '求助',
                        'channel_id' => '5',//日常闲聊
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '来人啊，大事不好了',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '汇总',
                        'channel_id' => '5',//日常闲聊
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '做了一点微小的工作',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '安利分享',
                        'channel_id' => '5',//日常闲聊
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '断头、吐血，吃我一口毒奶',
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '文字游戏',
                        'channel_id' => '5',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '主题讨论',
                        'channel_id' => '5',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//（板块：随笔 Channel N2.6)
                    DB::table('tags')->insert([
                        'tag_name' => '随笔',
                        'channel_id' => '6',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '散文',
                        'channel_id' => '6',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '诗歌',
                        'channel_id' => '6',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '日志',
                        'channel_id' => '6',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '剧本',
                        'channel_id' => '6',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '脑洞',
                        'channel_id' => '6',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '翻译',
                        'channel_id' => '6',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '影评乐评',
                        'channel_id' => '6',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '杂文',
                        'channel_id' => '6',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//（板块：站务管理 Channel No.7）账号、建议意见、bug汇报、站务公告
                    DB::table('tags')->insert([
                        'tag_name' => '日常交互',
                        'channel_id' => '7',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '账号问题',
                        'channel_id' => '7',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '建议意见',
                        'channel_id' => '7',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => 'bug汇报',
                        'channel_id' => '7',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '站务公告',
                        'channel_id' => '7',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//22（板块：违规举报 Channel No.8）人身攻击、信息泄露、待处理、处理中、历史记录
                    DB::table('tags')->insert([
                        'tag_name' => '日常违规',
                        'channel_id' => '8',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '人身攻击',
                        'channel_id' => '8',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '信息泄露',
                        'channel_id' => '8',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '违规记录',
                        'channel_id' => '8',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//23（板块：投诉仲裁 Channel No.9）管理投诉、权益仲裁、待处理、处理中、历史记录
                    DB::table('tags')->insert([
                        'tag_name' => '管理投诉',
                        'channel_id' => '9',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '权益仲裁',
                        'channel_id' => '9',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '仲裁记录',
                        'channel_id' => '9',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//31 （板块：管理组 Channel No.10) 处理投诉、日常管理、仲裁相关
                    DB::table('tags')->insert([
                        'tag_name' => '处理投诉',
                        'channel_id' => '10',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '日常管理',
                        'channel_id' => '10',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                    DB::table('tags')->insert([
                        'tag_name' => '仲裁相关',
                        'channel_id' => '10',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//32 （板块：管理组 Channel No.11) 档案
                    DB::table('tags')->insert([
                        'tag_name' => '建站相关',
                        'channel_id' => '11',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//32 （板块：管理组 Channel No.12) 后花园
                    DB::table('tags')->insert([
                        'tag_name' => '后花园',
                        'channel_id' => '12',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
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
                    // DB::table('tags')->insert([
                    //     'tag_name' => '高H',
                    //     'is_bianyuan' => true,
                    //     'tag_type' => '床戏性质'
                    // ]);
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
                    // DB::table('tags')->insert([
                    //     'tag_name' => '清水',
                    //     'is_bianyuan' => false,
                    //     'tag_type' => '床戏性质'
                    // ]);
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

            }
            echo "end insert more tags.\n";
        }else{
            echo "finish inserting more default tags.\n";
        }
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
                        if($label->id===47){
                            //特别情况特别考虑
                            $label_tag = \App\Models\Tag::where('tag_name','=', '其他同人')->first();
                            $tag->parent_id = $label_tag->id;
                            $tag->save();
                        }else{
                            echo "cannot find new_label_tag->label_id=".$label->id."new_label_tag->tag_name=".$label->labelname."\n";
                        }
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
    public function updateThreadTable()
    {
        echo "start task3.1 updateThreadTable\n";
        if(!Schema::hasColumn('threads', 'total_char')){
            Schema::table('threads', function($table){
                $table->unsignedInteger('last_component_id')->default(0);
                $table->dateTime('add_component_at')->nullable();
                $table->string('creation_ip', 45)->nullable();//创建时IP地址
                $table->boolean('use_markdown')->default(false);
                $table->boolean('use_indentation')->default(true);
                $table->unsignedInteger('weighted_jifen')->default(0);
                $table->unsignedInteger('total_char')->default(0);
                echo "added new columns to threads table.\n";
            });
        }

        if(!Schema::hasColumn('threads', 'jifen_count')){
            Schema::table('threads', function($table){
                $table->renameColumn('locked', 'is_locked');
                $table->renameColumn('delete_body', 'body');
                $table->renameColumn('public', 'is_public');
                $table->renameColumn('bianyuan', 'is_bianyuan');
                $table->renameColumn('anonymous', 'is_anonymous');
                $table->renameColumn('viewed', 'view_count');
                $table->renameColumn('responded', 'reply_count');
                $table->renameColumn('lastresponded_at', 'responded_at');
                $table->renameColumn('collection', 'collection_count');
                $table->renameColumn('noreply', 'no_reply');
                $table->renameColumn('downloaded', 'download_count');
                $table->renameColumn('jifen', 'jifen_count');
                echo "echo renamed threads columns.\n";
            });
        }
    }
    public function modifyThreadTable()
    {
        echo "start task3.2 modifyThreadTable\n";
        echo "start modify threads table body and other data and sync their old tags\n";
        \App\Models\Thread::chunk(1000, function ($threads) {
            foreach ($threads as $thread) {
                $post = $thread->mainpost;
                $thread->body = $post->body;
                $thread->creation_ip = $post->user_ip;
                $thread->use_markdown = $post->markdown;
                $thread->use_indentation = $post->indentation;
                $sync_tags = [];
                if($thread->book_id>0){
                    $book = $thread->book;
                    if($book->id>0){
                        $thread->weighted_jifen = $book->weighted_jifen;
                        $last_chapter = $book->last_chapter;
                        $thread->total_char = $book->total_char;
                        if($last_chapter){
                            $thread->last_component_id = $last_chapter->post_id;
                            $thread->add_component_at = $book->lastaddedchapter_at;
                        }
                        if($book->book_length>0){
                            $tag = $this->findTagByName(config('constants.book_info.book_lenth_info')[$book->book_length]);
                            if($tag){array_push($sync_tags,$tag->id);}
                        }

                        if($book->book_status>0){
                            $tag = $this->findTagByName(config('constants.book_info.book_status_info')[$book->book_status]);
                            if($tag){array_push($sync_tags,$tag->id);}
                        }
                        if($book->sexual_orientation>0){
                            $tag = $this->findTagByName(config('constants.book_info.sexual_orientation_info')[$book->sexual_orientation]);
                            if($tag){array_push($sync_tags,$tag->id);}
                        }
                        $tongren = $book->tongren;
                        if($tongren->id>0){
                            if($tongren->tongren_yuanzhu_tag_id){
                                array_push($sync_tags,$tongren->tongren_yuanzhu_tag_id);
                            }
                            if($tongren->tongren_CP_tag_id){
                                array_push($sync_tags,$tongren->tongren_CP_tag_id);
                            }
                        }
                    }
                }
                if($thread->top){
                    $tag = $this->findTagByName('置顶');
                    array_push($sync_tags,$tag->id);
                }
                $thread->save();

                $label_tag = $this->findTagByLabelId($thread->label_id);
                if($label_tag){
                    array_push($sync_tags,$label_tag->id);
                }else{
                    echo "when syncing tags to threads, cannot find old_label->label_id=".$thread->label_id."\n";
                }
                $thread->tags()->syncWithoutDetaching($sync_tags);
                $tags = $thread->tags;
                foreach($tags as $tag){
                    if($tag->channel_id>0&&$tag->channel_id!=$thread->channel_id){
                        echo "when syncing tags to threads, has problem of thread-tags not belong to same channel:".$thread->id."\n";
                    }
                }
            }
            echo $thread->id."|";
        });
    }
    public function findTagByName($tagname)
    {
        $tag = \App\Models\Tag::where('tag_name','=', $tagname)->first();
        if(!$tag){
            echo "cannot find tag=".$tagname."|";
        }
        return $tag;
    }
    public function findTagByLabelId($label_id)
    {
        $label_tag = null;
        $label = \App\Models\Label::where('id','=', $label_id)->first();
        if($label){
            $label_tag = \App\Models\Tag::where('tag_name','=', $label->labelname)->where('is_primary', true)->first();
            if(!$label_tag){
                echo "cannot find label_tag:label_id=".$label->id."label_name=".$label->labelname."\n";
            }
        }else{
            echo "cannot find old_label->label_id=".$label_id."\n";
        }
        return $label_tag;
    }

    public function modifyTongrenTable()
    {
        echo "start task4 modifyTongrenTable\n";
        if(!Schema::hasColumn('tongrens', 'thread_id')){
            Schema::table('tongrens', function($table){
                $table->unsignedInteger('thread_id');
            });
            echo "added thread_id column to tongrens\n";
        }
        if(Schema::hasColumn('tongrens', 'id')){
            $tongrens = \App\Models\Tongren::all();
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
        }
        DB::table('tongrens')->where('thread_id', '=', 0)->delete();
        DB::table('tongrens')->where('tongren_yuanzhu', '=', null)->where('tongren_cp', '=', null)->delete();
        if(Schema::hasColumn('tongrens', 'book_id')){
            Schema::table('tongrens', function($table){
                $table->dropColumn(['id','book_id','tongren_CP_tag_id', 'tongren_yuanzhu_tag_id', 'deleted_at','created_at','updated_at']);
            });
            echo "dropped tongrens table extra columns\n";
        }
    }

    public function modifyPostTable()
    {
        echo "start task5 modifyPostTable\n";
        $this->modifyPostTableColumns();//tas 5.1
        $this->modifyChapterTableColumns();//task 5.2
        $this->moveChapterToPost();//task 5.3
        $this->updatePostTypeColumn();//task 5.4
        $this->modifyPostCommentTable();//task 5.5
        $this->movePostCommentToPost();//task 5.6
    }
    public function modifyPostTableColumns()
    {
        echo "start task5.1 modifyPostTableColumns\n";
        if(!Schema::hasColumn('posts', 'type')){
            Schema::table('posts', function($table){
                $table->string('type',10)->nullable();
                //$table->string('title')->nullable();
                $table->string('reply_brief')->nullable();
                $table->unsignedInteger('reply_position')->default(0);

                $table->string('brief')->nullable();
                $table->unsignedInteger('reply_count')->default(0);
                $table->unsignedInteger('view_count')->default(0);
                $table->unsignedInteger('char_count')->default(0);
                echo "added new post columns.\n";
            });
        }

        if(!Schema::hasColumn('posts', 'upvote_count')){
            Schema::table('posts', function($table){
                $table->renameColumn('up_voted', 'upvote_count');
                $table->renameColumn('fold_state', 'is_folded');
                $table->renameColumn('anonymous', 'is_anonymous');
                $table->renameColumn('lastresponded_at', 'responded_at');
                $table->renameColumn('reply_to_post_id', 'reply_id');
                $table->renameColumn('markdown', 'use_markdown');
                $table->renameColumn('indentation', 'use_indentation');
                $table->renameColumn('user_ip', 'creation_ip');
                $table->renameColumn('bianyuan', 'is_bianyuan');
                $table->index('reply_id');//增加一个index
                echo "renamed post columns.\n";
            });
        }
    }
    public function modifyChapterTableColumns()
    {
        echo "start task5.2 modifyChapterTableColumns\n";
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
        echo "start task5.3 moveChapterToPost\n";
        \App\Models\Chapter::chunk(1000, function ($chapters) {
            foreach ($chapters as $chapter) {
                $post = $chapter->mainpost;
                if($post->id>0){
                    if($post->maintext){
                        $post->type='chapter';
                    }
                    $post->view_count = $chapter->viewed;
                    if($post->brief != $post->title){
                        $post->brief = $post->title;
                    }
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
    public function updatePostReply()
    {
        echo "start task6 updatePostReply\n";
        \App\Models\Post::with('replies')->chunk(1000, function ($posts) {
            foreach ($posts as $post) {
                $replies = $post->replies;
                $postbrief = \App\Helpers\Helper::trimtext($post->body, 50);
                if(!$post->brief){//对于以前没设置过brief的，填充brief
                    $post->brief = $postbrief;
                }
                $maxrespondedat = null;
                foreach($replies as $reply){
                    if($postbrief){
                        $reply->update(['reply_brief'=>$postbrief]);
                    }
                    $maxrespondedat = max($maxrespondedat,$reply->created_at);
                }
                $post->responded_at = max($maxrespondedat,$post->responded_at);
                $post->reply_count = count($replies);
                $post->char_count = iconv_strlen($post->body, 'utf-8');
                $post->save();
            }
            echo $post->id."|";
        });
        echo "finished task6 updatePostReply\n";
    }
    public function removeMainPost()
    {
        echo "start task7 removeMainPost\n";
        \App\Models\Thread::with('mainpost')->chunk(1000, function ($threads) {
            foreach ($threads as $thread) {
                $post = $thread->mainpost;
                if($post->id>0){
                    $post->delete();
                }
            }
            echo $post->id."|";
        });
        echo "finished task7 removeMainPost\n";
    }
    public function cleanupPostThreadChapterVolumn(){
        echo "start task8 cleanupPostThreadChapterVolumn\n";
        $this->cleanupThread();//task 8.1
        $this->cleanupChapter();//task 8.2
        $this->cleanupVolumn();//task 8.3
        $this->cleanupPost();//task 8.4
        echo "finished task8 cleanupPostThreadChapterVolumn\n";
    }
    public function cleanupThread()
    {
        echo "start task8.1 cleanupThread\n";
        if(Schema::hasColumn('threads', 'book_id')){
            Schema::table('threads', function($table){
                $table->dropColumn(['book_id','label_id','updated_at','post_id','top']);
            });
            echo "dropped old columns of thread table\n";
        }
    }
    public function cleanupChapter()
    {
        echo "start task8.2 cleanupChapter\n";
        if(Schema::hasColumn('chapters', 'book_id')){
            Schema::table('chapters', function($table){
                $table->dropColumn(['id','title','deleted_at','created_at','updated_at','characters','viewed','responded','edited_at']);
            });
            echo "dropped old columns of chapter table\n";
        }
    }
    public function cleanupVolumn()
    {
        echo "start task8.3 cleanupVolumn\n";
        if(Schema::hasColumn('volumns', 'book_id')){
            Schema::table('volumns', function($table){
                $table->dropColumn(['volumn_order','deleted_at','created_at','updated_at']);
                $table->renameColumn('book_id', 'thread_id');
            });
            echo "dropped old columns of volumn table\n";
        }
    }
    public function cleanupPost()
    {
        echo "start task8.4 cleanupPost\n";
        if(Schema::hasColumn('posts', 'maintext')){
            Schema::table('posts', function($table){
                $table->dropColumn(['down_voted','funny','fold','updated_at','chapter_id','maintext','long_comment','long_comment_id','popular','recommended','as_longcomment','trim_body']);
            });
            echo "dropped old columns of post table\n";
        }
    }

    public function removeBookPostCommentTable()
    {
        echo "start task9 removeBookPostCommentTable\n";
        Schema::dropIfExists('books');
        Schema::dropIfExists('post_comments');
        echo "finished task9 removeBookPostCommentTable\n";
    }

    public function removeDeletedThreadPost(){
        echo "start task10 removeDeletedThreadPost\n";
        $chapters = DB::table('chapters')
        ->join('posts','posts.id','=','chapters.post_id')
        ->join('threads','posts.thread_id','=','threads.id')
        ->where('threads.deleted_at','<>',null)
        ->select('chapters.post_id')
        ->get()
        ->pluck('post_id')
        ->toArray();
        DB::table('chapters')->whereIn('post_id',$chapters)->delete();
        echo "removed all thread deleted chapters\n";
        $posts = DB::table('posts')
        ->join('threads','posts.thread_id','=','threads.id')
        ->where('threads.deleted_at','<>',null)
        ->select('posts.id')
        ->get()
        ->pluck('id')
        ->toArray();
        DB::table('posts')->whereIn('id',$posts)->delete();
        echo "removed all thread deleted posts\n";
        DB::table('posts')->where('deleted_at','<>',null)->delete();
        echo "removed all deleted items from posts table\n";
        DB::table('threads')->where('deleted_at','<>',null)->delete();
        echo "removed all deleted items from threads table\n";
        echo "finished task10 removeDeletedThreadPost\n";
    }
    public function removeExtraTotalTables()//task 11
    {
        echo "start task11 removeExtraTotalTables\n";
        Schema::dropIfExists('cache');
        Schema::dropIfExists('collaborations');
        Schema::dropIfExists('comments_to_quotes');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('long_comments');
        Schema::dropIfExists('polls');
        Schema::dropIfExists('pool_responses');
        Schema::dropIfExists('session_statuses');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('channels');
        Schema::dropIfExists('labels');
        echo "finished task11 removeExtraTotalTables\n";
    }
    public function moveRecommendationToReview()
    {
        echo "start task12 moveRecommendationToReview\n";
        $this->createReviewTable();//task 12.1
        $this->createReviewList();//task 12.2
        echo "finished task12 moveRecommendationToReview\n";
    }
    public function createReviewTable()
    {
        echo "start task12.1 createReviewTable\n";
        Schema::create('reviews', function ($table) {
            $table->unsignedInteger('post_id')->primary();
            $table->unsignedInteger('thread_id')->default(0)->index();//以后也允许登记外站书籍
            $table->boolean('recommend')->default(true);//是否对外推荐
            $table->boolean('long')->default(false);//是否属于长评，字数超过几百字xx算长评
            $table->boolean('author_disapprove')->default(false);//作者不同意展示
            $table->boolean('editor_recommend')->default(false);//编辑推荐
            $table->tinyInteger('rating')->default(0);//评分，可以为零（不打分）
            $table->unsignedInteger('redirects')->default(0);//看完文评之后前进看书的比例
        });
        echo "finished task12.1 createReviewTable\n";
    }
    public function createReviewList()
    {
        echo "start task12.2 createReviewList\n";
        $editor = \App\Models\User::where('name','废文网编辑组')->first();

        $list_id = DB::table('threads')->insertGetId([
            'channel_id' => 13,
            'user_id' => $editor->id,
            'title' => '往期编推总楼',
            'brief' => '好文共赏',
            'body' => '存放往期推文，如果能找到对应的编辑，那么文章会存放在编辑账户下，否则归在本楼。',
        ]);
        echo "task 12.2 created main list \n";
        echo "task 12.2 start move review \n";
        $recommends = \App\Models\RecommendBook::all();
        foreach($recommends as $recommend){
            if($recommend->valid){
                if($recommend->long){
                    //长推
                    $recommendation_post = \App\Models\Post::find($recommend->thread_id);
                    $post_id = DB::table('posts')->insertGetId([
                        'thread_id'=> $list_id,
                        'brief' => $recommend->recommendation,
                        'body' => $recommendation_post->body,
                        'created_at' => $recommend->created_at,
                        'type' => 'review',
                    ]);

                    DB::table('reviews')->insert([
                        'post_id' => $post_id,
                        'thread_id'=> 0,
                        'recommend' => true,
                        'long' => true,
                        'editor_recommend' =>true,
                        'redirects' => $recommend->clicks,
                    ]);
                }else{
                    //短推
                    $post_id = DB::table('posts')->insertGetId([
                        'thread_id'=> $list_id,
                        'brief' => $recommend->recommendation,
                        'body' => $recommend->recommendation,
                        'created_at' => $recommend->created_at,
                        'type' => 'review',
                    ]);
                    DB::table('reviews')->insert([
                        'post_id' => $post_id,
                        'thread_id'=> $recommend->thread_id,
                        'recommend' => true,
                        'editor_recommend' =>true,
                        'redirects' => $recommend->clicks,
                    ]);
                }
                echo $post_id,"|";
            }
        }
        echo "finished task12.2 createReviewList\n";
    }
    public function moveCollectionListToReview()
    {
        $this->createNewCollectionList();//task 13.1
    }

    public function createNewCollectionList()//13.1
    {
        echo "start task13.1 createNewCollectionList\n";
        if(!Schema::hasColumn('collection_lists', 'new_list_id')){
            Schema::table('collection_lists', function($table){
                $table->unsignedInteger('new_list_id')->default(0);
                echo "added new_list_id to collection_lists as a column.\n";
            });
        }
        echo "task13.1 createList:";
        $collectionlists = \App\Models\CollectionList::all();
        foreach($collectionlists as $collectionlist){
            if($collectionlist->type<4){
                $list_id = DB::table('threads')->insertGetId([
                    'user_id' => $collectionlist->user_id,
                    'title' => $collectionlist->title,
                    'brief' => $collectionlist->brief,
                    'body' => $collectionlist->body,
                    'view_count' => $collectionlist->viewed,
                    'edited_at' => $collectionlist->lastupdated_at,
                    'created_at' => $collectionlist->created_at,
                    'is_anonymous' => $collectionlist->anonymous,
                    'majia' => $collectionlist->majia,
                ]);
                echo $list_id."|";
                $collectionlist->update(['new_list_id' => $list_id]);
                $collections = DB::table('collections')
                ->where('collection_list_id',$collectionlist->id)
                ->get();
                foreach($collections as $collection){
                    $post_id = DB::table('posts')->insertGetId([
                        'thread_id'=> $list_id,
                        'brief' => $collection->brief,
                        'body' => $collection->body,
                        'created_at' => $collection->lastupdated_at,
                        'type' => 'review',
                        'is_anonymous' => $collectionlist->anonymous,
                        'majia' =>$collectionlist->majia,
                    ]);

                    DB::table('reviews')->insert([
                        'post_id' => $post_id,
                        'thread_id'=> $collection->item_id,
                        'recommend' => true,
                    ]);
                }
            }
        }
        echo "\ntask13.1 finished create list\nstart rephrase collections";
        $collectionlists = \App\Models\CollectionList::all();
        foreach($collectionlists as $collectionlist){
            if($collectionlist->type===4){
                $collections = \App\Models\Collection::where('collection_list_id',$collectionlist->id)
                ->get();
                foreach($collections as $collection){
                    $list_collected = DB::table('collection_lists')
                    ->where('id', $collection->item_id)
                    ->first();
                    $collection->update([
                        'item_id' => $list_collected->new_list_id,
                        'collection_list_id' => 0,
                    ]);
                }
            }
        }
        echo "task13.1 updated collections table\n";
    }
    public function updateCollectionsTable()
    {
        echo "start task14 updateCollectionsTable\n";
        DB::table('collections')->where('collection_list_id','>',0)->delete();
        echo "task14 deleted list items\n";
        if(Schema::hasColumn('collections', 'updated')){
            Schema::table('collections', function($table){
                $table->renameColumn('item_id', 'thread_id');
                $table->renameColumn('updated', 'is_updated');
                //echo "echo renamed collections column.\n";
            });
        }
        if(Schema::hasColumn('collections', 'collection_list_id')){
            Schema::table('collections', function($table){
                $table->dropColumn(['collection_list_id','brief','body','lastupdated_at','delete_thread_id']);
                echo "echo dropped collections columns.\n";
            });
        }
        Schema::dropIfExists('collection_lists');
        echo "echo dropped collection_list table.\n";
    }
    public function moveQuestionToBox()//task 15
    {
        $this->createBoxes();//task 15.1
        $this->insertQuestionAnswerToBox();//task 15.2
        $this->deleteQuestionAnswerTable();//task 15.3
        $this->recalculateCharAndReplies();//task 15.4
    }
    public function createBoxes()//task 15.1
    {
        echo "task 15.1 createBoxes\n";
        $questions = \App\Models\Question::all();
        foreach($questions as $question){
            if($question->answer_id>0){
                $user = \App\Models\User::find($question->user_id);
                $thread = \App\Models\Thread::where('user_id',$question->user_id)
                ->where('channel_id', 14)
                ->first();
                if(!$thread){
                    $thread_id = DB::table('threads')
                    ->insertGetId([
                        'user_id' => $question->user_id,
                        'title' => $user->name."的问题箱",
                        'brief' => "欢迎向我提问哦！",
                        'created_at' => $question->created_at,
                        'channel_id' => 14,
                    ]);
                }
            }
        }
        echo "created boxes.\n";

    }
    public function insertQuestionAnswerToBox()//15.2
    {
        echo "task 15.2 insertQuestionAnswerToBox\n";
        $questions = \App\Models\Question::all();
        foreach($questions as $question){
            $thread = \App\Models\Thread::where('user_id',$question->user_id)
            ->where('channel_id', 14)
            ->first();
            $question_brief = \App\Helpers\Helper::trimtext($question->question_body, 50);
            $question_id = DB::table('posts')
            ->insertGetId([
                'user_id' => $question->questioner_id,
                'creation_ip' => $question->questioner_ip,
                'brief' => $question_brief,
                'body' => $question->question_body,
                'is_anonymous' => true,
                'created_at' => $question->created_at,
                'type' => 'question',
            ]);
            $answer = $question->answer;
            if($answer->id>0){
                $answer_id = DB::table('posts')
                ->insertGetId([
                    'user_id' => $question->user_id,
                    'brief' => \App\Helpers\Helper::trimtext($answer->answer_body, 50),
                    'body' => $answer->answer_body,
                    'created_at' => $answer->created_at,
                    'type' => 'answer',
                    'reply_id' => $question_id,
                    'reply_brief' => $question_brief,
                ]);
            }
        }
        echo "inserted questions and answers.\n";
    }
    public function deleteQuestionAnswerTable()//task 15.3
    {
        echo "task 15.3 delete questions and answers.\n";
        Schema::dropIfExists('questions');
        Schema::dropIfExists('answers');
    }
    public function recalculateCharAndReplies()//15.4
    {
        echo "task 15.4 recalculateCharAndReplies\n";
        \App\Models\Post::with('replies')->chunk(1000, function ($posts) {
            foreach($posts as $post){
                $post->update([
                    'reply_count' => $post->replies->count(),
                    'char_count' => iconv_strlen($post->body, 'utf-8'),
                ]);
            }
            echo $post->id."|";
        });
    }
    public function updateQuotes()//task 16
    {
        echo "task 16 update quotes table\n";
        if (Schema::hasColumn('quotes', 'quote')){
            Schema::table('quotes', function($table){
                $table->renameColumn('quote', 'body');
                $table->renameColumn('anonymous', 'is_anonymous');
                $table->renameColumn('approved', 'is_approved');
                $table->unsignedInteger('reviewer_id')->default(0);
                $table->dropColumn(['reviewed', 'updated_at']);
                echo "echo updated quotes table.\n";
            });
        }
    }
    public function updateStatuses()//task 17
    {
        echo "start task 17 updateStatuses\n";
        DB::table('statuses')->where('content','like','%]更新了《%')
        ->delete();
        DB::table('statuses')->where('content','like','%<p>更新了[url=%')
        ->delete();
        if (Schema::hasColumn('statuses', 'content')){
            Schema::table('statuses', function($table){
                $table->renameColumn('content', 'body');
                $table->string('attachable_type',10)->nullable();
                $table->unsignedInteger('attachable_id')->default(0);
                $table->unsignedInteger('reply_id')->default(0);
                $table->boolean('no_reply')->default(false);
                $table->unsignedInteger('reply_count')->default(0);
                $table->unsignedInteger('forward_count')->default(0);
                $table->unsignedInteger('upvote_count')->default(0);

                $table->dropColumn(['updated_at']);
                echo "echo updated statuses table.\n";
            });
        }
    }
    public function updateVotesTable()//18
    {
        echo "start task 18 updateVotesTable\n";
        if (!Schema::hasTable('votes')) {
            Schema::create('votes', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->index();
                $table->string('votable_type',10)->nullable()->index();
                $table->unsignedInteger('votable_id')->default(0)->index();
                $table->string('attitude_type',10)->nullable()->index();
                $table->dateTime('created_at')->nullable();
                $table->unique(['user_id','votable_type','votable_id','attitude_type']);
            });
            echo "created votes table\n";
        }
        echo "started inserting votes to table\n";
        \App\Models\VotePosts::chunk(1000, function ($votes) {
            foreach($votes as $vote){
                $post = \App\Models\Post::find($vote->post_id);
                $user = \App\Models\User::find($vote->user_id);
                if($post&&$user){
                    if($vote->upvoted){
                        $this->insertVote($vote->user_id,'post',$vote->post_id,'upvote',$vote->upvoted_at);
                    }
                    if($vote->downvoted){
                        $this->insertVote($vote->user_id,'post',$vote->post_id,'downvote',$vote->downvoted_at);
                    }
                    if($vote->funny){
                        $this->insertVote($vote->user_id,'post',$vote->post_id,'funnyvote',$vote->funny_at);
                    }
                    if($vote->better_to_fold){
                        $this->insertVote($vote->user_id,'post',$vote->post_id,'foldvote',$vote->better_to_fold_at);
                    }
                }
            }
            echo $vote->id."|";
        });
    }
    public function insertVote($user_id, $votable_type='post', $votable_id, $attitude_type, $created_at)//method for task 18
    {
        $vote = DB::table('votes')->where('user_id', $user_id)
        ->where('votable_type', $votable_type)
        ->where('votable_id', $votable_id)
        ->where('attitude_type', $attitude_type)
        ->first();
        if(!$vote){
            DB::table('votes')->insert([
                'user_id' => $user_id,
                'votable_type' => $votable_type,
                'votable_id' => $votable_id,
                'attitude_type' => $attitude_type,
                'created_at' => $created_at,
            ]);
        }
    }
    public function finalWrapUpTables()//task 19
    {
        Schema::dropIfExists('recommend_books');
        Schema::dropIfExists('shengfans');
        Schema::dropIfExists('xianyus');
        Schema::dropIfExists('vote_posts');
        Schema::table('public_notices', function($table){
            $table->dropColumn(['updated_at']);
        });
        Schema::table('linkaccounts', function($table){
            $table->dropColumn(['id']);
            $table->primary(['account1','account2']);
        });
        $links = DB::table('linkaccounts')->get();
        foreach($links as $link){
            $reverselink = DB::table('linkaccounts')
            ->where('account1',$link->account2)
            ->where('account2',$link->account1)
            ->first();
            if(!$reverselink){
                DB::table('linkaccounts')->insert([
                    'account1' => $link->account2,
                    'account2' => $link->account1,
                ]);
            }
        }
        Schema::table('messages', function($table){
            $table->dropColumn(['private','deleted_at','updated_at']);
            $table->renameColumn('message_body', 'message_body_id');
        });
    }
    public function remakeSystemsTables()//task 20
    {
        echo "start task20 remakeSystemsTables\n";
        Schema::dropIfExists('firewall');
        Schema::create('firewall', function ($table) {
            $table->string('ip_address', 45)->primary();//被封禁IP地址
            $table->unsignedInteger('user_id')->default(0);//执行封禁的管理员id
            $table->string('reason')->nullable();//封禁理由
            $table->dateTime('created_at')->nullable();//创建时间
            $table->dateTime('end_at')->nullable();//停止封禁时间
            $table->boolean('is_valid')->default(true);//是否可用
            $table->boolean('is_public')->default(true);//对外公示
        });
        Schema::create('role_user', function ($table) {
            $table->unsignedInteger('user_id');
            $table->string('role', 20);//身份标志
            $table->json('options')->nullable();//如果是对应的channel，或者homework，会有一个id注明可以弄哪些
            $table->primary(['user_id', 'role']);
            $table->string('reason')->nullable();//授权理由
            $table->dateTime('created_at')->nullable();//创建时间
            $table->dateTime('end_at')->nullable();//停止时间
            $table->boolean('is_valid')->default(true);//是否可用
            $table->boolean('is_public')->default(false);//对外公示
        });
        Schema::create('tag_post', function ($table) {
            $table->unsignedInteger('tag_id')->index();
            $table->unsignedInteger('post_id')->index();
            $table->primary(['post_id', 'tag_id']);
        });
        Schema::create('title_user', function ($table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('title_id');
            $table->boolean('is_public')->default(true);//对外公示
            $table->primary(['user_id', 'title_id']);
        });
        Schema::create('titles', function ($table) {
            $table->increments('id');
            $table->string('name',10)->nullable();//头衔名称
            $table->text('description')->nullable();//头衔解释
            $table->unsignedInteger('user_count')->default(0);//多少人获得了这个头衔
        });

        echo "finished task20 remakeSystemsTables\n";
    }
    public function modifyUserTable()//task 21
    {
        $this->createUserRelatedTables();//task 21.1
        $this->updateRoleAndTitle();//task 21.2
        $this->updatedUserInfoProfile();//task 21.3
        $this->deleteExtraUserColumns();//
    }
    public function createUserRelatedTables()//task 21.1
    {
        echo "start task21.1 modifyUserTable\n";
        Schema::create('user_profiles', function ($table) {
            $table->integer('user_id')->primary();
            $table->text('body')->nullable();

        });
        echo "created user_profiles table\n";
        Schema::create('user_infos', function ($table) {
            $table->integer('user_id')->primary();
            $table->integer('user_level')->default(0);//
            $table->string('brief')->nullable();//用户一句话简介
            $table->string('invitation_token')->nullable();//邀请码记录
            $table->string('login_ip', 45)->nullable();//最后一次登陆时IP地址
            $table->dateTime('login_at')->nullable();//最后一次登陆时间
            $table->string('majia', 10)->nullable();//最近使用过的马甲
            $table->boolean('indentation')->default(true);//最近使用过的段首缩进设置
            $table->unsignedInteger('sangdian')->default(0);//丧点数目
            $table->unsignedInteger('shengfan')->default(0);//剩饭数目
            $table->unsignedInteger('xianyu')->default(0);//咸鱼数目
            $table->unsignedInteger('jifen')->default(0);//积分数目
            $table->unsignedInteger('exp')->default(0);//经验值=盐度
            $table->unsignedInteger('upvote_count')->default(0);//被赞次数
            $table->unsignedInteger('downvote_count')->default(0);//被踩次数
            $table->unsignedInteger('funnyvote_count')->default(0);//被认为搞笑次数
            $table->unsignedInteger('foldvote_count')->default(0);//被认为需要折叠次数
            $table->unsignedInteger('continued_qiandao')->default(0);//连续签到次数
            $table->unsignedInteger('max_qiandao')->default(0);//最高连续签到次数
            $table->dateTime('last_qiandao_at')->nullable();//最后一次签到时间
            $table->unsignedInteger('reviewed_public_notices')->default(0);//已读系统消息数目
            $table->boolean('no_stranger_messages')->default(false);//是否拒绝接受陌生人的私信
            $table->boolean('no_upvote_reminders')->default(false);//是否不再接受关于被点赞的提醒
            $table->unsignedInteger('total_book_characters')->default(0);//全部发文字数
            $table->unsignedInteger('total_comment_characters')->default(0);//全部评论字数
            $table->unsignedBigInteger('total_clicks')->default(0);//全部点击次数
            $table->unsignedInteger('daily_clicks')->default(0);//今日点击次数
        });
        echo "created user_infos table\n";
        Schema::table('users', function($table){
            $table->unsignedInteger('title_id')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            //$table->unsignedInteger('unread_reminders')->default(0);//未读消息提醒
            $table->unsignedInteger('unread_updates')->default(0);//未读更新提示
        });
        echo "added columns to users table\n";
        {
            DB::table('titles')->insert([
                'name' => '大咸者',
                'description' => '用户等级大于7',
            ]);
            DB::table('titles')->insert([
                'name' => '初来乍到',
                'description' => '新注册咸鱼',
            ]);
            DB::table('titles')->insert([
                'name' => '编辑',
                'description' => '废文网编辑组成员',
            ]);
            DB::table('titles')->insert([
                'name' => '管理员',
                'description' => '废文网管理员',
            ]);
            DB::table('titles')->insert([
                'name' => '资深咸鱼',
                'description' => '在废文深水遨游、咸之又咸的鱼。',
            ]);
        }
        echo "done task 21.1 create user related tables\n";
    }

    public function updateRoleAndTitle()//task 21.2
    {
        echo "task 21.2 insert special user roles-good ones\n";
        $special_users = \App\Models\User::where('group','>',10)->get();

        foreach($special_users as $user){
            if($user->admin){
                DB::table('role_user')->insert([
                    'user_id' => $user->id,
                    'role' => 'admin',
                    'reason' => '管理员',
                    'is_public' => true,
                    'created_at' => Carbon::now(),
                ]);
                $this->addTitleToUser($user->id,'管理员');
            }else{
                if($user->group>=25){
                    DB::table('role_user')->insert([
                        'user_id' => $user->id,
                        'role' => 'senior-user',
                        'reason' => '资深咸鱼',
                        'created_at' => Carbon::now(),
                    ]);
                    $this->addTitleToUser($user->id,'资深咸鱼');
                }
                if($user->group===20){
                    DB::table('role_user')->insert([
                        'user_id' => $user->id,
                        'role' => 'editor',
                        'reason' => '编辑',
                        'is_public' => true,
                        'created_at' => Carbon::now(),
                    ]);
                    $this->addTitleToUser($user->id,'编辑');
                }
                if($user->group===15){
                    DB::table('role_user')->insert([
                        'user_id' => $user->id,
                        'role' => 'homeworker',
                        'reason' => '作业用户',
                        'created_at' => Carbon::now(),
                    ]);
                }
            }
        }
        echo "insert special user roles-forbidden ones\n";
        $special_users = \App\Models\User::where('no_logging','>',Carbon::now()->toDateTimeString())->get();
        foreach($special_users as $user){
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role' => 'no-login',
                'reason' => '违规封禁',
                'is_public' => true,
                'created_at' => Carbon::now(),
            ]);
        }
        echo "finished task 21.2 update title and roles of special users.\n";
    }

    public function updatedUserInfoProfile()//task 21.3
    {
        echo "task 21.3 start modifying users one by one\n";
        \App\Models\User::chunk(1000, function ($users) {
            foreach($users as $user){
                $user_data = [
                    'email_verified_at' => $user->activation_token? null:$user->created_at,
                ];
                $user_profile = [
                    'user_id' => $user->id,
                    'body' => $user->introduction,
                ];
                $user_info = [
                    'user_id' => $user->id,
                    'user_level' => $user->user_level,
                    'brief' => \App\Helpers\Helper::trimtext($user->introduction, 50),
                    'invitation_token' => $user->invitation_token,
                    'login_ip' => $user->last_login_ip,
                    'login_at' => $user->last_login,
                    'majia' => $user->majia,
                    'indentation' =>$user->indentation,
                    'sangdian' => $user->sangdian,
                    'shengfan' => $user->shengfan,
                    'xianyu' => $user->xianyu,
                    'jifen' => $user->jifen,
                    'exp' => $user->experience_points,
                    'upvote_count' => $user->upvoted,
                    'downvote_count' => $user->downvoted,
                    'continued_qiandao' => $user->continued_qiandao,
                    'max_qiandao' =>$user->maximum_qiandao,
                    'last_qiandao_at' => $user->lastrewarded_at,
                    'reviewed_public_notices' => $user->public_notices,
                    'no_stranger_messages' => !$user->receive_messages_from_strangers,
                    'no_upvote_reminders' => $user->no_upvote_reminders,
                ];
                if($user_data['email_verified_at']){
                    $user->update($user_data);
                }
                if($user_profile['body']){
                    DB::table('user_profiles')->insert($user_profile);
                }
                DB::table('user_infos')->insert($user_info);
            }
            echo $user->id."|";
        });
    }

    public function deleteExtraUserColumns()
    {
        echo "task 21.4 clean up users table\n";
        Schema::table('users', function($table){
            $table->dropColumn(['activation_token','user_level','sangdian','shengfan','xianyu','jifen','experience_points','upvoted','downvoted','continued_qiandao','maximum_qiandao','lastrewarded_at','public_notices','receive_messages_from_stranger','no_upvote_reminders','lastresponded_at','introduction','viewed','invitation_token','last_login_ip','last_login','admin','superadmin','group','no_posting','no_logging','guarden_deadline','post_reminders','postcomment_reminders','message_reminders','collection_threads_updated','collection_books_updated','collection_statuses_updated','majia','message_limit','no_registration','upvote_reminders','no_logging_or_not','total_char','lastsearched_at','indentation','system_reminders','collection_lists_updated','collection_list_limit','clicks','daily_clicks','daily_posts','daily_chapters','daily_characters','updated_at','reply_reminders','replycomment_reminders']);
        });
    }
    public function addTitleToUser($user_id,$title_name)//method for user-title task 21
    {
        $title = DB::table('titles')->where('name', $title_name)->first();
        if($title){
            DB::table('title_user')->insert([
                'user_id' => $user_id,
                'title_id' => $title->id,
            ]);
        }else{
            echo "cannot find title:".$title_name."\n";
        }
    }
}
