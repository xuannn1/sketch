<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThreadIdToRegisterHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_homeworks', function (Blueprint $table) {
            $table->integer('thread_id')->unsigned()->default(0)->index();//所交作业的id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register_homeworks', function (Blueprint $table) {
            $table->dropcolumn('thread_id');
        });
    }
}
