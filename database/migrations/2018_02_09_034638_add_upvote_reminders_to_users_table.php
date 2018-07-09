<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpvoteRemindersToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('upvote_reminders')->default(0);//赞赏提醒
            $table->boolean('no_upvote_reminders')->default(0);//是否不提醒他人赞赏
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
            $table->dropcolumn('upvote_reminders');
            $table->dropcolumn('no_upvote_reminders');
        });
    }
}
