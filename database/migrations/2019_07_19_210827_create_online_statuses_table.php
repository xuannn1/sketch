<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_statuses', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->primary();//登陆的用户名
            $table->dateTime('online_at')->nullable()->index();//最后何时在线
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_statuses');
    }
}
