<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');//post_id
            $table->string('type',10)->nullable()->index();//'chapter', 'question', 'answer', 'request', 'post', 'comment', 'review', 'poll'...
            $table->unsignedInteger('user_id')->default(0)->index();//作者id
            $table->unsignedInteger('thread_id')->default(0)->index();//讨论帖id

            $table->string('title', 30)->nullable();//标题
            $table->string('brief', 50)->nullable();//节选
            $table->text('body')->nullable();//回帖文本本身
            $table->boolean('is_anonymous')->default(false);//是否匿名回帖
            $table->string('majia', 10)->nullable();//作者马甲
            $table->string('creation_ip', 45)->nullable()->index();//创建时IP地址
            $table->dateTime('created_at')->nullable()->index();//创建时间
            $table->dateTime('edited_at')->nullable()->index();//最后编辑时间

            $table->unsignedInteger('in_component_id')->default(0)->index();//从属单元id
            $table->unsignedInteger('reply_to_id')->default(0)->index();//如果是回帖，给出它回复对象的id
            $table->string('reply_to_brief')->nullable();//如果是回帖，给出它回复对象的brief
            $table->unsignedInteger('reply_to_position')->default(0)->index();//回复对象句子在原来评论中的位置
            $table->unsignedInteger('last_reply_id')->default(0)->index();//最新回复id
            $table->boolean('is_bianyuan')->default(false);//是否属于边缘内容（以至于需要对非注册用户隐藏内容）
            $table->boolean('use_markdown')->default(false);//是否使用md语法
            $table->boolean('use_indentation')->default(true);//是否使用段首缩进格式

            $table->unsignedInteger('upvote_count')->default(0)->index();//赞
            $table->unsignedInteger('downvote_count')->default(0)->index();//踩
            $table->unsignedInteger('funnyvote_count')->default(0)->index();//搞笑
            $table->unsignedInteger('foldvote_count')->default(0)->index();//折叠

            $table->unsignedInteger('reply_count')->default(0)->index();//得到的回复数
            $table->unsignedInteger('view_count')->default(0)->index();//得到的单独点击数
            $table->unsignedInteger('char_count')->default(0)->index();//总字数

            $table->dateTime('responded_at')->nullable();//最后被回应时间
            $table->dateTime('deleted_at')->nullable()->index();// 软删除必备

            $table->tinyInteger('fold_state')->default(0)->index();//折叠

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
