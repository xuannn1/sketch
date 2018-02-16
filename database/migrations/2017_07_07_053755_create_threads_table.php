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
        Schema::create('threads', function (Blueprint $table) {//讨论区主题
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();//发表者，楼主
            $table->integer('book_id')->unsigned()->default(0)->index();
            $table->string('title')->index();//标题
            $table->string('brief')->nullable();//一句话简介
            $table->text('body')->nullable();//正文
            $table->boolean('locked')->default(false);//是否锁定
            $table->boolean('public')->default(true);//是否仅为作者可见
            $table->boolean('bianyuan')->default(false);//是否边缘文章
            $table->boolean('anonymous')->default(false);//匿名
            $table->string('majia', 10)->nullable();//马甲
            $table->integer('shengfan')->unsigned()->default(0);//剩饭
            $table->integer('xianyu')->unsigned()->default(0);//咸鱼
            $table->integer('viewed')->unsigned()->default(0);//浏览
            $table->integer('responded')->unsigned()->default(0);//回应数
            $table->dateTime('lastresponded_at')->default(Carbon\Carbon::now())->index();
            $table->tinyInteger('channel_id')->unsigned()->default(0);//channel，板块
            $table->integer('label_id')->unsigned()->default(0);
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
        Schema::dropIfExists('threads');
    }
}
