<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_homeworks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('homework_id')->unsigned()->index();//作业ID
            $table->integer('user_id')->unsigned()->index();//注册参加作业的人ID
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
        Schema::dropIfExists('register_homeworks');
    }
}
