<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitation_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->index();
            $table->string('token')->index();
            $table->unsignedInteger('invited')->default(0)->index();
            $table->unsignedInteger('invitation_times')->default(0)->index();
            $table->dateTime('invite_until')->nullable()->index();
            $table->dateTime('deleted_at')->nullable()->index();
            $table->dateTime('created_at')->nullable()->index();
            $table->dateTime('updated_at')->nullable()->index();
            $table->boolean('is_public')->default(true)->index();
            $table->integer('token_level')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitation_tokens');
    }
}
