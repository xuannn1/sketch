<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('volumn_id')->default(0);//分卷号
            $table->unsignedInteger('thread_id')->default(0);//书籍编号
            $table->integer('order_by')->default(0);//排序号码
            $table->unsignedInteger('post_id')->default(0);
            $table->string('title')->nullable();//标题
            $table->string('brief')->nullable();//简介
            $table->string('annotation')->nullable();//备注
            $table->boolean('annotation_infront')->default(false);//默认章节内注释位置
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapters');
    }
}
