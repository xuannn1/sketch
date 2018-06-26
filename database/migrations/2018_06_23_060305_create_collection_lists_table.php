<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('private')->default(0);//是否公开
            $table->tinyInteger('type')->unsigned()->default(0);//类型 1:书籍收藏 2:讨论帖收藏 3:帖子收藏 4:对收藏夹的收藏
            $table->string('title')->index();//收藏列表名称
            $table->string('brief')->nullable();//收藏列表简介
            $table->text('body')->nullable();//收藏列表详细介绍
            $table->integer('user_id')->unsigned()->index();//谁创造的这个列表
            $table->integer('item_number')->unsigned();//收藏夹内所包含thread数目
            $table->integer('xianyu')->unsigned()->default(0);//收藏夹被投掷咸鱼数
            $table->integer('shengfan')->unsigned()->default(0);//收藏夹被投掷剩饭数
            $table->integer('collected')->unsigned()->default(0);//收藏夹被收藏数
            $table->integer('viewed')->unsigned()->default(0);//收藏夹被收藏数
            $table->integer('last_item_id')->unsigned()->default(0);//最后更新的条目是
            $table->dateTime('lastupdated_at')->default(Carbon\Carbon::now());//最后一次更新在什么时间
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_lists');
    }
}
