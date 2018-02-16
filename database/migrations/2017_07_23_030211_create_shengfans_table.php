<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShengfansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shengfans', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('shengfan_num')->default(0);//这次投喂了多少剩饭
            $table->integer('user_id')->unsigned()->index();//投掷剩饭者用户名
            $table->integer('post_id')->unsigned()->index();//属于哪个post
            $table->timestamps('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shengfans');
    }
}
