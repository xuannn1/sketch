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
            $table->integer('user_id')->unsigned()->primary();
            $table->integer('shengfan')->unsigned()->default(0);
            $table->integer('xianyu')->unsigned()->default(0);
            $table->integer('jifen')->unsigned()->default(0);
            $table->integer('sangdian')->unsigned()->default(0);
            $table->integer('exp')->unsigned()->default(0);
            $table->integer('upvote_count')->unsigned()->default(0);
            $table->integer('follower_count')->unsigned()->default(0);
            $table->integer('following_count')->unsigned()->default(0);
            $table->string('brief_intro', 50)->nullable();// 极简介绍
            $table->text('introduction')->nullable();// 全部介绍
            $table->integer('views')->unsigned()->default(0);
            $table->string('activation_token', 50)->nullable();//邮箱验证码
            $table->string('invitation_token', 50)->nullable();//邀请码
            $table->dateTime('no_posting_until')->nullable();//禁言时限
            $table->dateTime('no_logging_until')->nullable();//禁止登陆时限
            $table->integer('continued_qiandao')->unsigned()->default(0);
            $table->integer('max_qiandao')->unsigned()->default(0);
            $table->integer('message_limit')->unsigned()->default(0);
            $table->integer('list_limit')->unsigned()->default(0);
            $table->boolean('no_stranger_msg')->default(false);//是否接受来自陌生人的私信
            $table->boolean('no_upvote_reminders')->default(false);//是否接受点赞提醒
            $table->integer('clicks')->unsigned()->default(0);
            $table->integer('daily_clicks')->unsigned()->default(0);
            $table->integer('reply_reminders')->unsigned()->default(0);
            $table->integer('upvote_reminders')->unsigned()->default(0);
            $table->integer('message_reminders')->unsigned()->default(0);
            $table->integer('reward_reminders')->unsigned()->default(0);
            $table->integer('public_notices')->unsigned()->default(0); //已经阅读的公共通知数量
            $table->integer('collection_threads_updates')->unsigned()->default(0);
            $table->integer('collection_books_updates')->unsigned()->default(0);
            $table->unsignedInteger('default_list_id')->unsigned()->default(0);
            $table->unsignedInteger('default_box_id')->unsigned()->default(0);
            $table->string('login_ip')->nullable();//上一次访问的ip地址
            $table->dateTime('login_at')->nullable();//上次登录时间
            $table->dateTime('active_at')->nullable();//上次活跃时间
            $table->dateTime('edited_at')->nullable();//简介修改于

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
