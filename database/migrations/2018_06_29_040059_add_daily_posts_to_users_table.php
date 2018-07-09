<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyPostsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('daily_posts')->default(0);//今日(非章节)发帖数
            $table->integer('daily_chapters')->default(0);//今日发文统计数
            $table->integer('daily_characters')->default(0);//今日在站上留言总字数
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
            $table->dropcolumn('daily_posts');
            $table->dropcolumn('daily_chapters');
            $table->dropcolumn('daily_characters');
        });
    }
}
