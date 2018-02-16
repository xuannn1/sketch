<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChapterToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
           $table->integer('chapter_id')->unsigned()->default(0)->index();//属于哪个章节
           $table->boolean('maintext')->default(false);//是否为正文内容
           $table->string('title')->nullable();//评论标题
           $table->boolean('long_comment')->default(false);//是否属于长评
           $table->integer('long_comment_id')->unsigned()->default(0);//对应长评的id
           $table->integer('reply_to_post_id')->unsigned()->default(0);//是否属于回帖，回帖id多少
           $table->boolean('popular')->default(false);//是否热门
           $table->boolean('recommended')->default(false);//是否被版主推荐
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropcolumn('chapter_id');
            $table->dropcolumn('maintext');
            $table->dropcolumn('title');
            $table->dropcolumn('long_comment');
            $table->dropcolumn('long_comment_id');
            $table->dropcolumn('reply_to_post_id');
            $table->dropcolumn('popular');
            $table->dropcolumn('recommended');
        });
    }
}
