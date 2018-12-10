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
            //$table->timestamps();
            $table->unsignedInteger('user_id')->index();//作者id
            $table->unsignedInteger('book_id')->index();//是否是图书，是的话书籍信息id
            $table->unsignedInteger('channel_id')->index();//是哪个频道下面的
            $table->unsignedInteger('label_id')->index();//是哪个大类下面的
            $table->string('title')->nullable();//讨论帖总标题
            $table->string('brief')->nullable();//讨论帖的一个小灰字简介
            $table->unsignedInteger('post_id')->index();//主要的mainpost是谁
            $table->dateTime('last_responded_at')->nullable();//最后被回应时间
            $table->boolean('is_thread')->default(true);//(还可以是quote讨论，question_box，其他结构的东西等等)
            $table->boolean('is_locked')->default(false);//是否锁帖
            $table->boolean('is_public')->default(true);//是否公开
            $table->boolean('is_bianyuan')->default(false);//是否边缘限制
            $table->boolean('no_reply')->default(false);//是否禁止回复
            $table->boolean('is_top')->default(false);//是否置顶
            $table->boolean('is_popular')->default(false);//是否飘火
            $table->boolean('is_highlighted')->default(false);//是否加高亮
            $table->integer('views')->default(0);//观看次数
            $table->integer('replies')->default(0);//被回复次数
            $table->integer('collections')->default(0);//被收藏次数
            $table->integer('downloads')->default(0);//被下载次数
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
        Schema::dropIfExists('threads');
    }
}
