<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricalUsersDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_users_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0)->index();//用户id
            $table->string('created_at', 11)->default('0000-00-00')->index();//记录登记日期
            $table->integer('daily_clicks')->unsigned()->default(0);//今日点击数
            $table->integer('daily_posts')->default(0);//今日发帖数
            $table->integer('daily_chapters')->default(0);//今日发帖统计数
            $table->integer('daily_characters')->default(0);//今日留言总字数
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
         Schema::dropIfExists('historical_users_data');
     }
}
