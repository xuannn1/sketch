<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Helpers\Helper;
use Carbon\Carbon;
use Auth;

trait ModifyThreadTableTraits{

    public function modifyThreadTable()//task 02
    {
        $this->modifyTagThreadTable();//task 2.1
        $this->modifyTagTable();//task 2.2
        $this->updateThreadTable();//task 2.3
        $this->insertThreadTable();//task 2.4
        $this->syncOldThreadTags();//
        $this->modifyTongrenTable();//task 2.5
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
                $table->renameColumn('books', 'thread_count');
                $table->renameColumn('tagname', 'tag_name');
                $table->unique('tag_name');
                $table->index('parent_id');
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
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        {
            DB::table('tags')->insert([
                'tag_name' => '短篇',
                'tag_type' => '篇幅',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '中篇',
                'tag_type' => '篇幅',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '长篇',
                'tag_type' => '篇幅',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '大纲',
                'tag_type' => '篇幅',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        {
            DB::table('tags')->insert([
                'tag_name' => '连载',
                'tag_type' => '进度',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '完结',
                'tag_type' => '进度',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '暂停',
                'tag_type' => '进度',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        {
            DB::table('tags')->insert([
                'tag_name' => 'BL',
                'tag_type' => '性向',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => 'GL',
                'tag_type' => '性向',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => 'BG',
                'tag_type' => '性向',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => 'GB',
                'tag_type' => '性向',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '混合性向',
                'tag_type' => '性向',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '无CP',
                'tag_type' => '性向',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '其他性向',
                'tag_type' => '性向',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        {

            DB::table('tags')->insert([
                'tag_name' => '荤素均衡',
                'is_bianyuan' => false,
                'tag_type' => '床戏性质',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '肉渣',
                'is_bianyuan' => false,
                'tag_type' => '床戏性质',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }
        {
            DB::table('tags')->insert([
                'tag_name' => '专题推荐',
                'tag_type' => '编推',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '当前编推',
                'tag_type' => '编推',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '往期编推',
                'tag_type' => '编推',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '高亮',
                'tag_type' => '管理',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '置顶',
                'tag_type' => '管理',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '精华',
                'tag_type' => '管理',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        {
            DB::table('tags')->insert([
                'tag_name' => '想读',
                'tag_type' => '阅读进度',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '正在读',
                'tag_type' => '阅读进度',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '养肥',
                'tag_type' => '阅读进度',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '弃文',
                'tag_type' => '阅读进度',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '已读',
                'tag_type' => '阅读进度',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '多次阅读',
                'tag_type' => '阅读反馈',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tags')->insert([
                'tag_name' => '推荐',
                'tag_type' => '阅读反馈',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
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
                $table->unsignedInteger('first_component_id')->default(0);
                $table->dateTime('add_component_at')->nullable();
                $table->string('creation_ip', 45)->nullable();//创建时IP地址
                $table->boolean('markdown')->default(false);
                $table->boolean('indentation')->default(true);
                $table->unsignedInteger('weighted_jifen')->default(0);
                $table->unsignedInteger('total_char')->default(0);
                $table->index('created_at');
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
            'threads.indentation' => DB::raw('posts.indentation'),
            'posts.deleted_at' => DB::raw('threads.created_at')
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
            'threads.last_component_id' => DB::raw('chapters.post_id'),
            'threads.add_component_at' => DB::raw('books.lastaddedchapter_at'),
        ]);
    }

    public function syncOldThreadTags()
    {
        echo "start sync threads old tags\n";

        Cache::put('allTags', \App\Models\Tag::all(), 10);
        Cache::put('allLabels', \App\Models\Label::all(), 10);

        \App\Models\Thread::with('book.tongren')->chunk(1000, function ($threads) {
            $insert_tags = [];
            foreach ($threads as $thread) {
                if($thread->book_id>0){
                    $book = $thread->book;
                    if($book->id>0){
                        if($book->book_length>0){
                            $tag = $this->findTagByName(config('constants.book_info.book_length_info')[$book->book_length]);
                            if($tag){array_push($insert_tags,['tag_id'=>$tag->id,'thread_id'=>$thread->id]);}
                            else{echo "no tag".$book->book_length;}
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
                if($thread->jinghua>Carbon::now()){
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
        return Helper::find_tag_by_name($tagname);
    }
    public function findTagByLabelId($label_id)
    {
        return Helper::find_tag_by_label_id($label_id);
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
        DB::table('tongrens')
        ->join('books','books.id','=','tongrens.book_id')
        ->update([
            'tongrens.thread_id' => DB::raw('books.thread_id')
        ]);
        echo "updated thread_id column to tongrens\n";
        DB::table('tongrens')
        ->where('tongren_yuanzhu_tag_id','>',0)
        ->update(['tongren_yuanzhu'=>null]);
        echo "updated tongren_yuanzhu column to tongrens\n";
        DB::table('tongrens')
        ->where('tongren_CP_tag_id','>',0)
        ->update(['tongren_cp'=>null]);
        echo "updated tongren_cp column to tongrens\n";
        DB::table('tongrens')
        ->where('tongren_yuanzhu','=',null)
        ->where('tongren_cp','=',null)
        ->delete();
        echo "deleted unnecessary columns of tongrens\n";

        if(Schema::hasColumn('tongrens', 'book_id')){
            Schema::table('tongrens', function($table){
                $table->dropColumn(['book_id','tongren_CP_tag_id', 'tongren_yuanzhu_tag_id', 'deleted_at','created_at','updated_at']);
            });
            echo "dropped tongrens table extra columns\n";
        }
    }

}
