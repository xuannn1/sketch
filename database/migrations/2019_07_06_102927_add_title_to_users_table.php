<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('title_id')->unsigned()->default(0);
            $table->integer('unread_reminders')->unsigned()->default(0);
            $table->integer('unread_updates')->unsigned()->default(0);
            $table->string('role',10)->nullable();
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
            $table->dropcolumn('title_id');
            $table->dropcolumn('role');
            $table->dropcolumn('unread_reminders');
            $table->dropcolumn('unread_updates');
        });
    }
}
