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
            $table->tinyInteger('chapter_order')->unsigned()->default(0);//章节order，暗示在整个书中的排列顺序（从小到大）
            $table->string('annotation')->nullable();//作者有话说
            $table->integer('post_id')->unsigned();//对应正文内容存放的post
            $table->integer('book_id')->unsigned()->index();//对应书本id
            $table->integer('volumn_id')->unsigned()->default(0);//对应卷id
            $table->string('title');//章节名
            $table->softDeletes();
            $table->timestamps();
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
