<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel_name')->nullable();//板块名称
            $table->string('channel_explanation')->nullable();//板块简介
            $table->integer('order_by')->default(0);//板块排序方式
            $table->text('channel_rule')->nullable();//板块版规
            $table->integer('channel_state')->default(0);//板块权限编码：0:水区，1:书籍，2:投诉，10作业， 更大的数字是仅管理可见的版面
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
