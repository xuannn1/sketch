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
            $table->unsignedInteger('post_id')->index();
            $table->unsignedInteger('volumn_id')->default(0);//分卷号
            $table->unsignedInteger('thread_id')->index();//书籍编号
            $table->integer('order_by')->default(0);//排序号码
            $table->string('title')->nullable();//标题
            $table->string('brief')->nullable();//简介
            $table->text('annotation')->nullable();//章节备注
            $table->boolean('annotation_infront')->default(false);//默认章节内注释位置
            $table->unsignedInteger('previous_chapter_id')->default(0);//前一章的章节序数（post_id）
            $table->unsignedInteger('next_chapter_id')->default(0);//后一章的章节序数（post_id）
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
