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
            $table->unsignedInteger('user_id')->index();//作者id
            $table->unsignedInteger('thread_id')->index();//讨论帖id
            $table->text('body')->nullable();//回帖文本本身
            $table->string('preview')->nullable();//节选
            $table->boolean('is_anonymous')->default(false);//是否匿名回帖
            $table->string('majia', 10)->nullable();//作者马甲
            $table->string('creation_ip', 45)->nullable();//创建时IP地址
            $table->dateTime('created_at')->nullable();//创建时间
            $table->dateTime('last_edited_at')->nullable();//最后编辑时间
            $table->unsignedInteger('reply_to_post_id')->default(0)->index();//如果是回帖，给出它回复对象的id
            $table->string('reply_to_post_preview')->nullable();//如果是回帖，给出它回复对象的preview
            $table->unsignedInteger('reply_position')->default(0);//回复对象句子在原来评论中的位置
            $table->string('type',20)->nullable();//'chapter','collection','question','answer','request'
            $table->boolean('is_component')->default(false);//是否是正文章节
            $table->boolean('is_post_comment')->default(false);//是否是二级评论
            $table->boolean('use_markdown')->default(false);//是否使用md语法
            $table->boolean('use_indentation')->default(true);//是否使用段首缩进格式
            $table->unsignedInteger('up_votes')->default(0);//赞
            $table->unsignedInteger('down_votes')->default(0);//踩
            $table->unsignedInteger('fold_votes')->default(0);//折叠
            $table->unsignedInteger('funny_votes')->default(0);//搞笑
            $table->unsignedInteger('xianyus')->default(0);//得到的咸鱼
            $table->unsignedInteger('shengfans')->default(0);//得到的咸鱼
            $table->unsignedInteger('replies')->default(0);//得到的回复数
            $table->boolean('is_folded')->default(false);//是否属于折叠状态
            $table->boolean('allow_as_longpost')->default(true);//作者是否允许展示为长评
            $table->boolean('is_bianyuan')->default(false);//是否属于边缘内容（以至于需要对非注册用户隐藏内容）
            $table->dateTime('last_responded_at')->nullable();//最后被回应时间
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
