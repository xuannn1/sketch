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
            $table->string('type', 20)->nullable();//板块属性book,thread,collection_list,column,request,homework
            $table->boolean('allow_anonymous')->default(true);//是否允许匿名建楼/回帖
            $table->boolean('allow_edit')->default(true);//是否允许普通用户修改
            $table->boolean('is_public')->default(true);//是否公众可见
            $table->boolean('on_homepage')->default(true);//是否首页显示
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
