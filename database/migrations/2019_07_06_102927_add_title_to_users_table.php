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
            $table->string('role',10)->nullable();
            $table->integer('unread_updates')->unsigned()->default(0);
            $table->dateTime('qiandao_at')->nullable()->index();//上次签到时间
            $table->TinyInteger('quiz_level')->unsigned()->default(0);//最后做题等级
            $table->boolean('no_posting_or_not')->default(false);//是否被禁言
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
            $table->dropcolumn('unread_updates');
            $table->dropcolumn('qiandao_at');
            $table->dropcolumn('quiz_level');
            $table->dropcolumn('no_posting_or_not');
        });
    }
}
