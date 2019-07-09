<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ModifyPostTableTraits{

    public function modifyPostTable()
    {
        echo "start task4 modifyPostTable\n";
        $this->modifyPostTableColumns();//tas 1
        $this->modifyChapterTableColumns();//task 2
        $this->moveChapterToPost();//task 3
        $this->updatePostTypeColumn();//task 4
        $this->movePostCommentToPost();//task 5
        $this->updatePostReply();//task 6
    }
    public function modifyPostTableColumns()
    {
        echo "start task4.1 modifyPostTableColumns\n";
        if(!Schema::hasColumn('posts', 'type')){
            Schema::table('posts', function($table){
                $table->string('type',10)->nullable();
                $table->string('reply_to_brief')->nullable();
                $table->unsignedInteger('reply_to_position')->default(0);
                $table->unsignedInteger('last_reply_id')->default(0);
                $table->unsignedInteger('reply_count')->default(0);
                $table->unsignedInteger('view_count')->default(0);
                $table->unsignedInteger('char_count')->default(0);
                $table->unsignedInteger('postcomment_id')->default(0)->index();
                $table->unsignedInteger('in_component_id')->default(0)->index();
                echo "added new post columns.\n";
            });
        }
        if(!Schema::hasColumn('posts', 'upvote_count')){
            Schema::table('posts', function($table){
                $table->renameColumn('trim_body', 'brief');
                $table->renameColumn('lastresponded_at', 'responded_at');
                $table->renameColumn('up_voted', 'upvote_count');
                $table->renameColumn('fold_state', 'is_folded');
                $table->renameColumn('reply_to_post_id', 'reply_to_id');
                $table->renameColumn('user_ip', 'creation_ip');
                $table->index('reply_to_id');//增加一个index
                $table->index('type');//增加一个index
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
        DB::table('posts')
        ->join('chapters','posts.id','=','chapters.post_id')
        ->where('posts.maintext','=',1)
        ->update([
            'posts.type' => 'chapter',
            'posts.view_count' => DB::raw('chapters.viewed'),
            'posts.brief' => DB::raw('posts.title'),
            'posts.edited_at' => DB::raw('chapters.edited_at'),
        ]);

        DB::table('posts')
        ->join('chapters','posts.id','=','chapters.post_id')
        ->where('posts.maintext','=',1)
        ->update([
            'posts.title' => DB::raw('chapters.title'),
        ]);

        echo "finished moveChapterToPost\n";
    }

    public function updatePostTypeColumn()
    {
        echo "start task4.4 updatePostTypeColumn\n";
        DB::table('posts')
        ->where('maintext','=',0)
        ->update(['type'=>'post']);

        DB::table('posts')
        ->where('maintext','=',0)
        ->where('reply_to_id','>',0)
        ->update(['type'=>'comment']);

        DB::table('posts')
        ->where('maintext','=',0)
        ->where('reply_to_id','>',0)
        ->update(['type'=>'comment']);

        DB::table('posts')
        ->join('chapters','chapters.id','=','posts.chapter_id')
        ->where('posts.maintext','=',0)
        ->where('posts.reply_to_id','>',0)
        ->update([
            'posts.in_component_id' => DB::raw('chapters.post_id')
        ]);

        echo "updated comment type.\n";
        DB::table('posts')
        ->join('chapters','chapters.id','=','posts.chapter_id')
        ->where('posts.maintext','=',0)
        ->where('posts.reply_to_id','=',0)
        ->update([
            'posts.reply_to_id' => DB::raw('chapters.post_id'),
            'posts.in_component_id' => DB::raw('chapters.post_id')
        ]);

        DB::table('posts as p1')
        ->join('posts as p2','p2.reply_to_id','=','p1.id')
        ->update(['p1.last_reply_id'=>DB::raw('p2.id')]);

        echo "finished updatePostTypeColumn\n";
    }

    public function movePostCommentToPost()
    {
        echo "start task4.6 movePostCommentToPost\n";
        \App\Models\PostComment::with('post.thread')->chunk(1000, function ($postcomments) {
            $insert_new_post = [];
            foreach ($postcomments as $postcomment) {
                $post = $postcomment->post;
                if($post->id>0&&$post->thread_id>0){
                    $post_data=[];
                    $post_data['postcomment_id'] =  $postcomment->id;
                    $post_data['body'] = $postcomment->body;
                    $post_data['majia'] = $postcomment->majia?? '';
                    $post_data['anonymous'] = $postcomment->anonymous;
                    $post_data['created_at'] = $postcomment->created_at;
                    $post_data['thread_id'] = $post->thread_id;
                    $post_data['user_id'] = $postcomment->user_id;
                    if($post->maintext){
                        $post_data['type']='post';
                        $post_data['reply_to_id']=$post->id;
                    }elseif($post->id===$post->thread->post_id){
                        $post_data['type']='post';
                        $post_data['reply_to_id']=0;
                    }else{
                        $post_data['type']='comment';
                        $post_data['reply_to_id']=$post->id;
                    }
                    array_push($insert_new_post, $post_data);
                }
            }
            DB::table('posts')->insert($insert_new_post);
            echo $postcomment->id.'|';
        });
        echo "finished task4.6 movePostCommentToPost\n";
    }

    public function updatePostReply() //task 06
    {
        echo "start task6 updatePostReply\n";
        DB::statement('
            UPDATE posts P1
            JOIN (
                SELECT reply_to_id, Count(reply_to_id) as ParentCount
                FROM posts
                GROUP BY reply_to_id
            ) P2 ON P1.id=P2.reply_to_id
            SET P1.reply_count=P2.ParentCount
        ');
        echo "counted replies for each post\n";
        DB::statement('
            UPDATE posts
            SET brief = SUBSTRING(brief,1,50)
            where brief is not null
        ');
        DB::statement('
            UPDATE posts
            SET brief = SUBSTRING(body,1,50)
            where brief is null
        ');
        echo "updated briefs as substring of body\n";

        DB::table("chapters")
        ->join('posts','posts.id','=','chapters.post_id')
	    ->update(['posts.char_count' => DB::raw('chapters.characters')]);
        echo "updated characters from chapters data\n";

        DB::table('posts as p1')
        ->join('posts as p2', 'p1.id','=','p2.reply_to_id')
        ->update(['p2.reply_to_brief'=>DB::raw('p1.brief')]);
        echo "finished task6 updatePostReply\n";
    }

}
