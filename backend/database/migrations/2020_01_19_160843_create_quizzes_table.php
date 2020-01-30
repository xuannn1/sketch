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
            $table->boolean('is_online')->default(true)->index();
            $table->string('type',10)->nullable()->index();
            $table->integer('quiz_level')->default(0)->index();
            $table->text('body')->nullable();
            $table->text('hint')->nullable();
            $table->unsignedInteger('quiz_count')->default(0)->index();
            $table->unsignedInteger('correct_count')->default(0)->index();
            $table->dateTime('created_at')->nullable()->index();
            $table->dateTime('edited_at')->nullable()->index();
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
