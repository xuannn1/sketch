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
            $table->unsignedInteger('user_id')->default(0)->index();
            $table->unsignedInteger('receiver_id')->default(0)->index();
            $table->unsignedInteger('votable_id')->default(0)->index();
            $table->string('votable_type',10)->nullable()->index();
            $table->string('attitude_type', 10)->nullable()->index();  //upvote,downvote,funnyvote,foldvote
            $table->dateTime('created_at')->nullable()->index();
            $table->unique(['user_id','votable_type', 'votable_id','attitude_type']);
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
