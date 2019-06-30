<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('body')->nullable();//题干
            $table->text('hint')->nullable();//提示
            $table->boolean('is_valid')->default(true);//是否仍然有效
            $table->integer('quiz_counts')->unsigned()->default(0);//已经被回答过多少次
            $table->integer('correct_counts')->unsigned()->default(0);//已经被正确回答过多少次
            $table->integer('quiz_level')->default(0);//题目等级
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
        Schema::dropIfExists('quizzes');
    }
}
