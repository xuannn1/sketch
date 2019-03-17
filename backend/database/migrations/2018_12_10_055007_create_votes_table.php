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
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->morphs('votable');  //post,quote,status
            $table->string('attitude', 10);  //upvote,downvote,funnyvote,foldvote
            $table->dateTime('created_at')->nullable();
            $table->unique(['user_id','votable_type', 'votable_id','attitude']);
            $table->index(['user_id','votable_type', 'votable_id']);
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
