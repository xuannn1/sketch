<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        //$this->modifyTagThreadTable();
        //$this->modifyTagTable();
        $this->modifyThreadTable();

    }

    public function modifyTagThreadTable()
    {
        if(Schema::hasTable('tagging_threads')){
            Schema::rename('tagging_threads', 'tag_thread');
            echo "renamed tag_thread table\n";
        }
        if(Schema::hasTable('tag_thread')){
            if (Schema::hasColumn('tag_thread', 'id')){
                Schema::table('tag_thread', function($table){
                    $table->dropColumn(['id', 'created_at', 'updated_at']);
                });
                echo "dropped old columns\n";
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
        }
    }
    public function modifyTagTable()
    {
        echo "start modify tag table.\n";
        if(!Schema::hasColumn('tags', 'tag_type')){
            Schema::table('tags', function($table){
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesFound = $sm->listTableIndexes('tags');
                if(array_key_exists("tags_lastresponded_at_index", $indexesFound)){
                    $table->dropIndex('tags_lastresponded_at_index');
                    echo "removed old indexes.\n";
                }
            });
            Schema::table('tags', function($table){
                $table->string('tag_type', 10);
                $table->boolean('is_bianyuan')->default(false);
                $table->boolean('is_primary')->default(false);
                $table->unsignedInteger('channel_id')->default(0);//是否某个channel专属
                echo "echo added new columns.\n";
            });
            Schema::table('tags', function($table){
                $table->renameColumn('tag_belongs_to', 'parent_id');
                $table->renameColumn('books', 'book_count');
                $table->renameColumn('tagname', 'tag_name');
                echo "echo renamed columns.\n";
            });
        }
        if(Schema::hasColumn('tags', 'tag_group')){
            echo 'start modify existing tags.\n';
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
                        'tag_name' => '写作探讨',
                        'channel_id' => '4',
                        'tag_type' => '大类',
                        'is_primary' => true,
                        'tag_explanation' => '除了卡文，也许还可以做点别的',
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
                }
                {
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
                        'tag_name' => '戏剧',
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
                        'tag_name' => '其他随笔',
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
                        'tag_name' => '账号',
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
                        'tag_name' => '历史记录',
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
                        'tag_name' => '历史记录',
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
                {//32 （板块：管理组 Channel No.11) 后花园
                    DB::table('tags')->insert([
                        'tag_name' => '后花园',
                        'channel_id' => '12',
                        'tag_type' => '大类',
                        'is_primary' => true,
                    ]);
                }
                {//文库相关tag预设 （原创大类 同人大类）

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
            }
            echo "end insert more tags.\n";
        }else{
            echo "already inserted more tags.\n";
        }
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
    }
    public function modifyThreadTable()
    {

    }

}
