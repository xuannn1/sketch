<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReminderToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->integer('post_reminders')->default(0);//主题帖下有回帖提醒
           $table->integer('postcomment_reminders')->default(0);//点评提醒
           $table->integer('reply_reminders')->default(0);//回复贴子提醒
           $table->integer('replycomment_reminders')->default(0);//回复点评提醒
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
           $table->dropcolumn('post_reminders');//
           $table->dropcolumn('postcomment_reminders');//
           $table->dropcolumn('reply_reminders');//
           $table->dropcolumn('replycomment_reminders');//
        });
    }
}
