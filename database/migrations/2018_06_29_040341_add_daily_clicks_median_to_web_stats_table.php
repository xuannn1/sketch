<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyClicksMedianToWebStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_stats', function (Blueprint $table) {
            $table->integer('daily_clicks_median')->unsigned()->default(0);//每日点击统计中位数
            $table->integer('daily_clicks_average')->unsigned()->default(0);//每日点击统计平均数
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
            $table->dropcolumn('daily_clicks_median');
            $table->dropcolumn('daily_clicks_average');
        });
    }
}
