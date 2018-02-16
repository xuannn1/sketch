<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('quote');
            $table->integer('user_id')->unsigned();
            $table->boolean('anonymous')->default(false);//匿名
            $table->string('majia', 10)->nullable();//马甲
            $table->boolean('notsad')->default(false);
            $table->boolean('approved')->default(false);//通过审核
            $table->boolean('reviewed')->default(false);//是否被审核
            $table->integer('xianyu')->unsigned()->default(0);//本题头获得的咸鱼
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
        Schema::dropIfExists('quotes');
    }
}
