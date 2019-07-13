<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTokenRefreshTimeToSystemsVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_variables', function (Blueprint $table) {
            $table->dateTime('token_refreshed_at')->default(Carbon::now());//最近刷新过token的时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_variables', function (Blueprint $table) {
            $table->dropcolumn('token_refreshed_at');
        });
    }
}
