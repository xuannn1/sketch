<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->index();
            $table->string('token', 50)->nullable();
            $table->unsignedInteger('redeem_count')->default(0)->index();
            $table->unsignedInteger('redeem_limit')->default(0)->index();
            $table->dateTime('created_at')->nullable()->index();
            $table->dateTime('deleted_at')->nullable()->index();
            $table->string('type', 20)->nullable()->index();
            $table->boolean('is_public')->default(false)->index();
            $table->dateTime('redeem_until')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reward_tokens');
    }
}
