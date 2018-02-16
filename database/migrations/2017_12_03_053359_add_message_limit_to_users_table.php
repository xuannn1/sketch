<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageLimitToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('message_limit')->unsigned()->default(0);//每日给陌生人发私信的限额
            $table->boolean('receive_messages_from_stranger')->default(true);//是否接收来自陌生人的私信
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropcolumn('message_limit');//
            $table->dropcolumn('receive_messages_from_stranger');//
        });
    }
}
