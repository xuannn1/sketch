<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecommendBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommend_books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('thread_id')->unsigned()->index();//推荐了哪个thread
            $table->boolean('valid')->default(true);//是否仍然推荐
            $table->integer('clicks')->unsigned();//通过这个首页链接点击进去的数值统计
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
        Schema::dropIfExists('recommend_books');
    }
}
