<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworkRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homework_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('homework_id')->default(0)->index();
            $table->unsignedInteger('user_id')->default(0)->index();
            $table->string('role',10)->nullable()->index();
            $table->string('majia',10)->nullable()->index();
            $table->dateTime('registered_at')->nullable()->index();
            $table->dateTime('submitted_at')->nullable()->index();
            $table->dateTime('finished_at')->nullable()->index();
            $table->unsignedInteger('order_id')->default(0)->index();
            $table->unsignedInteger('thread_id')->default(0)->index();
            $table->string('title',30)->nullable()->index();
            $table->unsignedInteger('upvote_count')->default(0)->index();
            $table->unsignedInteger('received_critique_count')->default(0)->index();
            $table->unsignedInteger('given_critique_count')->default(0)->index();
            $table->unsignedInteger('required_critique_thread_id')->default(0)->index();
            $table->boolean('required_critique_done')->default(false)->index();
            $table->integer('summary')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homework_registrations');
    }
}
