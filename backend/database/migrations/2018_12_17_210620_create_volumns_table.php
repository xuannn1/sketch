<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volumns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('thread_id')->default(0);//属于哪本图书
            $table->string('title')->nullable();//卷标题
            $table->string('brief')->nullable();//卷简介
            $table->text('body')->nullable();//卷正文
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('volumns');
    }
}
