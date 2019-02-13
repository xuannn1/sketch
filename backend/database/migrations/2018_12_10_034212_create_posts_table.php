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
            $table->string('type',10)->nullable()->index();//'chapter', 'question', 'answer', 'request', 'post', 'comment', 'review', 'poll'
            $table->unsignedInteger('user_id')->index();//作者id
            $table->unsignedInteger('thread_id')->index();//讨论帖id

            $table->string('title')->nullable();//标题
            $table->string('brief')->nullable();//节选
            $table->text('body')->nullable();//回帖文本本身
            $table->boolean('is_anonymous')->default(false);//是否匿名回帖
            $table->string('majia', 10)->nullable();//作者马甲
            $table->string('creation_ip', 45)->nullable();//创建时IP地址
            $table->dateTime('created_at')->nullable();//创建时间
            $table->dateTime('edited_at')->nullable();//最后编辑时间

            $table->unsignedInteger('reply_id')->default(0)->index();//如果是回帖，给出它回复对象的id
            $table->string('reply_brief')->nullable();//如果是回帖，给出它回复对象的brief
            $table->unsignedInteger('reply_position')->default(0);//回复对象句子在原来评论中的位置
            $table->boolean('is_folded')->default(false);//是否属于折叠状态
            $table->boolean('is_bianyuan')->default(false);//是否属于边缘内容（以至于需要对非注册用户隐藏内容）
            $table->boolean('use_markdown')->default(false);//是否使用md语法
            $table->boolean('use_indentation')->default(true);//是否使用段首缩进格式

            $table->unsignedInteger('upvote_count')->default(0);//赞
            $table->unsignedInteger('reply_count')->default(0);//得到的回复数
            $table->unsignedInteger('view_count')->default(0);//得到的单独点击数
            $table->unsignedInteger('char_count')->default(0);//总字数

            $table->dateTime('responded_at')->nullable();//最后被回应时间
            $table->softDeletes();//软删除必备

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
