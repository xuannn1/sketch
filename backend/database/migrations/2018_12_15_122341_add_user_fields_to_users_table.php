<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserFieldsToUsersTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();//软删除必备
            $table->integer('user_level')->default(0);//
            $table->string('brief')->nullable();//用户一句话简介
            $table->unsignedInteger('sangdians')->default(0);//丧点数目
            $table->unsignedInteger('shengfans')->default(0);//剩饭数目
            $table->unsignedInteger('xianyus')->default(0);//咸鱼数目
            $table->unsignedInteger('jifens')->default(0);//积分数目
            $table->unsignedInteger('experience_points')->default(0);//经验值=盐度
            $table->unsignedInteger('up_votes')->default(0);//被赞次数
            $table->unsignedInteger('down_votes')->default(0);//被踩次数
            $table->unsignedInteger('funny_votes')->default(0);//被认为搞笑次数
            $table->unsignedInteger('fold_votes')->default(0);//被认为需要折叠次数
            $table->unsignedInteger('continued_qiandaos')->default(0);//连续签到次数
            $table->unsignedInteger('max_qiandaos')->default(0);//最高连续签到次数
            $table->dateTime('last_qiandao_at')->nullable();//最后一次签到时间
            $table->unsignedInteger('unread_reminders')->default(0);//未读消息提醒
            $table->unsignedInteger('unread_updates')->default(0);//未读更新提示
            $table->unsignedInteger('reviewed_public_notices')->default(0);//已读系统消息数目
            $table->string('recent_majia', 10)->nullable();//最近使用过的马甲
            $table->boolean('recent_indentation')->default(true);//最近使用过的分段偏好
            $table->unsignedInteger('message_limit')->default(0);//发送私信的限额
            $table->boolean('no_stranger_messages')->default(false);//是否拒绝接受陌生人的私信
            $table->boolean('no_upvote_reminders')->default(false);//是否不再接受关于被点赞的提醒
            $table->unsignedInteger('total_book_characters')->default(0);//全部发文字数
            $table->unsignedInteger('total_comment_characters')->default(0);//全部评论字数
            $table->unsignedInteger('daily_clicks')->default(0);//今日点击数
            $table->unsignedInteger('daily_posts')->default(0);//今日发贴数
            $table->unsignedInteger('daily_book_characters')->default(0);//今日发文字数
            $table->unsignedInteger('daily_comment_characters')->default(0);//今日发评字数
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
