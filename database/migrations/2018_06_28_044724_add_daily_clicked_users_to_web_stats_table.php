<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyClickedUsersToWebStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_stats', function (Blueprint $table) {
            $table->integer('daily_clicked_users')->unsigned()->default(0);//每日点击统计数
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('web_stats', function (Blueprint $table) {
            $table->dropcolumn('daily_clicked_users');
        });
    }
}
