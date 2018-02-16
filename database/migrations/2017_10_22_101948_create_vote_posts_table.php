<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();//投票者用户名
            $table->integer('post_id')->unsigned()->index();//post_ID
            $table->boolean('upvoted')->default(false);
            $table->boolean('downvoted')->default(false);
            $table->boolean('funny')->default(false);
            $table->boolean('better_to_fold')->default(false);
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
        Schema::dropIfExists('vote_posts');
    }
}
