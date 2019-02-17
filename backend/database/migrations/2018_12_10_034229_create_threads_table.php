<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();//作者id
            $table->unsignedInteger('channel_id')->default(0);//是哪个频道下面的

            $table->string('title')->nullable();//讨论帖总标题
            $table->string('brief')->nullable();//讨论帖简介小灰字
            $table->text('body')->nullable();//讨论帖正文
            $table->boolean('is_anonymous')->default(false);//是否匿名
            $table->string('majia', 10)->nullable();//作者马甲
            $table->string('creation_ip', 45)->nullable();//创建时IP地址
            $table->dateTime('created_at')->nullable();//创建时间
            $table->dateTime('edited_at')->nullable();//最后编辑时间

            $table->boolean('is_locked')->default(false);//是否锁帖
            $table->boolean('is_public')->default(true);//是否公开
            $table->boolean('is_bianyuan')->default(false);//是否边缘限制
            $table->boolean('no_reply')->default(false);//是否禁止回复

            $table->boolean('use_markdown')->default(false);//是否使用md语法
            $table->boolean('use_indentation')->default(true);//是否使用段首缩进格式

            $table->unsignedInteger('view_count')->default(0);//点击数
            $table->unsignedInteger('reply_count')->default(0);//得到的回复数
            $table->unsignedInteger('collection_count')->default(0);//被收藏次数
            $table->unsignedInteger('download_count')->default(0);//被下载次数
            $table->unsignedInteger('jifen')->default(0);//总积分
            $table->unsignedInteger('weighted_jifen')->default(0);//被字数模块平衡后的积分
            $table->unsignedInteger('total_char')->default(0);//components总字数

            $table->dateTime('responded_at')->nullable();//最后被回应时间
            $table->unsignedInteger('last_post_id')->default(0);//最后回帖是谁
            $table->dateTime('add_component_at')->nullable();//最后新增物品时间
            $table->unsignedInteger('last_component_id')->default(0);//最新物品

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
        Schema::dropIfExists('threads');
    }
}
