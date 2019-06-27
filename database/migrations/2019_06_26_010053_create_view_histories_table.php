<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('view_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip_address', 45)->index();
            $table->integer('user_id')->unsigned()->index();//用户id记录
            $table->integer('thread_id')->unsigned()->index();//用户浏览thread记录
            $table->integer('post_id')->unsigned()->index();//用户浏览章节记录
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
        Schema::dropIfExists('view_histories');
    }
}
