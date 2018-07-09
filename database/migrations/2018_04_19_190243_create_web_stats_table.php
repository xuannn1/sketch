<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qiandaos')->unsigned()->default(0);//今日签到数
            $table->integer('posts')->unsigned()->default(0);//今日新增回帖总数
            $table->integer('posts_maintext')->unsigned()->default(0);//今日新增章节数
            $table->integer('posts_reply')->unsigned()->default(0);//今日新增回复数
            $table->integer('post_comments')->unsigned()->default(0);//今日新增点评数
            $table->integer('new_users')->unsigned()->default(0);//今日新增用户数
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
        Schema::dropIfExists('web_stats');
    }
}
