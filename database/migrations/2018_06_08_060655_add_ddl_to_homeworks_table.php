<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDdlToHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homeworks', function (Blueprint $table) {
            $table->integer('hold_sangdian')->unsigned()->default(0);//报名克扣丧点
            $table->dateTime('register_at')->default(Carbon\Carbon::now());//第一次报名开始时间
            $table->integer('register_number')->unsigned()->default(0);//第一次报名限制人数
            $table->dateTime('register_at_b')->default(Carbon\Carbon::now());//第二次报名开始时间
            $table->integer('register_number_b')->unsigned()->default(0);//第二次报名限制人数
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homeworks', function (Blueprint $table) {
            $table->dropcolumn('hold_sangdian');
            $table->dropcolumn('register_at');
            $table->dropcolumn('register_number');
            $table->dropcolumn('register_at_b');
            $table->dropcolumn('register_number_b');
        });
    }
}
