<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupMessagingToMessageBodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_bodies', function (Blueprint $table) {
           $table->boolean('group_messaging')->default(0);//是否群发信息
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_bodies', function (Blueprint $table) {
            $table->dropcolumn('group_messaging');
        });
    }
}
