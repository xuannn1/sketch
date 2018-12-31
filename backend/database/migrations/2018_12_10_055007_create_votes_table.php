<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->index();//投票人
            $table->string('votable_type', 10);//;
            $table->unsignedInteger('votable_id')->index();//投票对象id
            $table->string('attitude_type', 10);//;
            $table->integer('attitude_value')->index(0);//;反馈数值（比如投多少剩饭等）
            $table->dateTime('created_at')->nullable();//创建时间
            $table->primary(['user_id', 'votable_type', 'votable_id', 'attitude_type']);
            $table->index(['votable_type', 'votable_id', 'attitude_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
