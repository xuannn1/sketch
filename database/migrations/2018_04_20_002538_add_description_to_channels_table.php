<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionToChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->string('description')->nullable();//一句话版面简介
            $table->text('regulations')->nullable();//版规
            $table->integer('recent_thread_1_id')->unsigned()->default(0);//最新thread
            $table->integer('recent_thread_2_id')->unsigned()->default(0);//最新thread
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropcolumn('description');
            $table->dropcolumn('regulations');
            $table->dropcolumn('recent_thread_1_id');
            $table->dropcolumn('recent_thread_2_id');
        });
    }
}
