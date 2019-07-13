<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpvoteTimeToVotePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vote_posts', function (Blueprint $table) {
            $table->dateTime('upvoted_at')->default(Carbon::now());//直到xx截止日期;
            $table->dateTime('downvoted_at')->default(Carbon::now());//直到xx截止日期;
            $table->dateTime('funny_at')->default(Carbon::now());//直到xx截止日期;
            $table->dateTime('better_to_fold_at')->default(Carbon::now());//直到xx截止日期;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vote_posts', function (Blueprint $table) {
            $table->dropcolumn('upvoted_at');
            $table->dropcolumn('downvoted_at');
            $table->dropcolumn('funny_at');
            $table->dropcolumn('better_to_fold_at');
        });
    }
}
