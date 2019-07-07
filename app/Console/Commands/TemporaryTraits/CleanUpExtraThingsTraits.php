<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait CleanUpExtraThingsTraits{

    public function cleanUpExtraThings()
    {
        $this->mainCleanUp();
        $this->simplifyBooks();
        $this->simplifyChapters();
        $this->simplifyThreads();
        $this->simplifyPosts();
    }
    public function mainCleanUp()
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('collaborations');
        Schema::dropIfExists('collection_lists');
        Schema::dropIfExists('comments_to_quotes');
        Schema::dropIfExists('long_comments');
        Schema::dropIfExists('polls');
        Schema::dropIfExists('pool_responses');
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('recommend_books');
        Schema::dropIfExists('shengfans');
        Schema::dropIfExists('vote_posts');
        Schema::dropIfExists('xianyus');

        echo "dropped extra tables\n";
    }

    public function simplifyBooks()
    {
        if (Schema::hasColumn('books', 'lastresponded_at')){
            Schema::table('books', function($table){
                $table->dropColumn(['original','lastresponded_at','lastaddedchapter_at','deleted_at','created_at','updated_at','total_char','last_chapter_id','indentation','weighted_jifen','tongren_yuanzhu','tongren_cp']);
                echo "simplified books table.\n";
            });
        }
    }
    public function simplifyChapters()
    {
        if (Schema::hasColumn('chapters', 'book_id')){
            Schema::table('chapters', function($table){
                $table->dropColumn(['book_id','title','deleted_at','created_at','updated_at','characters','viewed','responded','edited_at']);
                echo "simplified chapters table.\n";
            });
        }
    }
    public function simplifyThreads()
    {
        if (Schema::hasColumn('threads', 'label_id')){
            Schema::table('threads', function($table){
                $table->dropColumn(['book_id','label_id','updated_at','homework_id','post_id','show_homework_profile','top','jinghua','recommended']);
                echo "simplified threads table.\n";
            });
        }
    }

    public function simplifyPosts()
    {
        if (Schema::hasColumn('posts', 'long_comment')){
            Schema::table('posts', function($table){
                $table->dropColumn(['updated_at','chapter_id','long_comment','long_comment_id','popular','recommended','as_longcomment']);
                echo "simplified posts table.\n";
            });
        }
    }
}
