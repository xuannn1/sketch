<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('questioner_ip')->nullable()->index();//发问者ip
            $table->integer('questioner_id')->unsigned()->index()->default(0);//发问人ip
            $table->integer('user_id')->unsigned()->index();//对谁提问？对应的这个人的id
            $table->text('question_body')->nullable();//提问正文，也许存在会很长的情况
            $table->integer('answer_id')->unsigned()->index()->default(0);//答案id
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
        Schema::dropIfExists('questions');
    }
}
