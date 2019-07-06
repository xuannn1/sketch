<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserIntrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_intros', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->primary();
            $table->text('introduction')->nullable();//简介正文
            $table->dateTime('updated_at')->nullable();//更新于
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_intros');
    }
}
