<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXianyusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xianyus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_ip')->nullable()->index();//投掷咸鱼者ip
            $table->integer('user_id')->unsigned()->index();//投掷咸鱼者用户名
            $table->integer('thread_id')->unsigned()->index();//属于哪个主题
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
        Schema::dropIfExists('xianyus');
    }
}
