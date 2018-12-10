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
            //$table->timestamps();
            $table->unsignedInteger('user_id')->index();//作者id
            $table->unsignedInteger('thread_id')->index();//讨论帖id
            $table->string('title')->nullable();//标题
            $table->text('body')->nullable();//回帖文本本身
            $table->string('preview')->nullable();//帖子的一个简短的节选，方便对于帖子进行简单介绍
            $table->boolean('is_anonymous')->default(false);//是否匿名回帖
            $table->string('majia', 10)->nullable();//作者马甲
            $table->string('creation_ip', 45)->nullable();//创建时IP地址
            $table->dateTime('last_edited_at')->nullable();//最后编辑时间
            $table->unsignedInteger('last_editor_id')->index();//最后是谁编辑的（如果is_anonymous，这一项不应该显示给普通用户）
            $table->unsignedInteger('replied_post_id')->index();//回复对象post_id（这是否是一个针对其他回帖的回帖）
            $table->integer('reply_position')->default(0);//回复对象句子在原来评论中的位置
            $table->boolean('is_postcomment')->default(false);//是否属于点评（点评只显示在回帖下面，回复则单独起一行）
            $table->boolean('use_markdown')->default(false);//是否使用md语法
            $table->boolean('use_indentation')->default(false);//是否使用段首缩进格式
            $table->integer('up_votes')->default(0);//赞
            $table->integer('down_votes')->default(0);//踩
            $table->integer('fold_votes')->default(0);//折叠
            $table->integer('funny_votes')->default(0);//搞笑
            $table->integer('xianyus')->default(0);//得到的咸鱼
            $table->integer('shengfans')->default(0);//得到的咸鱼
            $table->integer('replies')->default(0);//得到的回复数
            $table->boolean('is_folded')->default(false);//是否属于折叠状态
            $table->boolean('is_popular')->default(false);//是否属于折叠状态
            $table->boolean('is_longpost')->default(false);//是否属于长评范围
            $table->boolean('allow_as_longpost')->default(false);//是否允许展示为长评
            $table->boolean('is_bianyuan')->default(false);//是否属于边缘内容（以至于需要对非注册用户隐藏内容）
            $table->dateTime('last_responded_at')->nullable();//最后被回应时间
            $table->dateTime('created_at')->nullable();//创建时间
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
