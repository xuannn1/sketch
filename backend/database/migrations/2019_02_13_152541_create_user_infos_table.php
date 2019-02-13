<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->integer('user_level')->default(0);//
            $table->string('brief')->nullable();//用户一句话简介
            $table->unsignedInteger('sangdian')->default(0);//丧点数目
            $table->unsignedInteger('shengfan')->default(0);//剩饭数目
            $table->unsignedInteger('xianyu')->default(0);//咸鱼数目
            $table->unsignedInteger('jifen')->default(0);//积分数目
            $table->unsignedInteger('exp')->default(0);//经验值=盐度
            $table->unsignedInteger('upvote_count')->default(0);//被赞次数
            $table->unsignedInteger('downvote_count')->default(0);//被踩次数
            $table->unsignedInteger('funnyvote_count')->default(0);//被认为搞笑次数
            $table->unsignedInteger('foldvote_count')->default(0);//被认为需要折叠次数
            $table->unsignedInteger('continued_qiandao')->default(0);//连续签到次数
            $table->unsignedInteger('max_qiandao')->default(0);//最高连续签到次数
            $table->dateTime('last_qiandao_at')->nullable();//最后一次签到时间
            // $table->unsignedInteger('unread_reminders')->default(0);//未读消息提醒
            // $table->unsignedInteger('unread_updates')->default(0);//未读更新提示
            // $table->unsignedInteger('reviewed_public_notices')->default(0);//已读系统消息数目
            // $table->unsignedInteger('message_limit')->default(0);//发送私信的限额
            // $table->boolean('no_stranger_messages')->default(false);//是否拒绝接受陌生人的私信
            // $table->boolean('no_upvote_reminders')->default(false);//是否不再接受关于被点赞的提醒
            // $table->unsignedInteger('total_book_characters')->default(0);//全部发文字数
            // $table->unsignedInteger('total_comment_characters')->default(0);//全部评论字数
            // $table->unsignedInteger('daily_clicks')->default(0);//今日点击数
            // $table->unsignedInteger('daily_posts')->default(0);//今日发贴数
            // $table->unsignedInteger('daily_book_characters')->default(0);//今日发文字数
            // $table->unsignedInteger('daily_comment_characters')->default(0);//今日发评字数
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_infos');
    }
}
