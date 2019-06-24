<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefreshTimesToInvitationTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invitation_tokens', function (Blueprint $table) {
            $table->integer('refresh_times')->default(0);//定时刷新数额
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invitation_tokens', function (Blueprint $table) {
            $table->dropcolumn('refresh_times');
        });
    }
}
