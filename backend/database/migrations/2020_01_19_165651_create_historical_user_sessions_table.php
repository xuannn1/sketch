<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricalUserSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_user_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->index();
            $table->dateTime('created_at')->nullable()->index();
            $table->unsignedInteger('session_count')->default(0)->index();
            $table->unsignedInteger('ip_count')->default(0)->index();
            $table->unsignedInteger('ip_band_count')->default(0)->index();
            $table->unsignedInteger('device_count')->default(0)->index();
            $table->unsignedInteger('mobile_count')->default(0)->index();
            $table->text('session_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historical_user_sessions');
    }
}
