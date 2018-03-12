<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();//下载人
            $table->integer('thread_id')->unsigned();//下载对象
            $table->tinyInteger('format')->unsigned()->default(0);//下载形式：0，普通讨论帖下载。 1，书本+评论形式（类似纯讨论帖，按时间排列）。 2，书本+评论（按章节顺序排列）。3.纯书本形式。
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
        Schema::dropIfExists('downloads');
    }
}
