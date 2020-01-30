<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('receiver_id')->default(0)->index();
            $table->unsignedInteger('rewardable_id')->default(0)->index();
            $table->string('rewardable_type',10)->nullable()->index();
            $table->unsignedInteger('reward_value')->default(0)->index();
            $table->string('reward_type',10)->nullable()->index();
            $table->dateTime('created_at')->nullable()->index();
            $table->dateTime('deleted_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rewards');
    }
}
