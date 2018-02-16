<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->integer('shengfan')->unsigned()->default(10);
           $table->integer('xianyu')->unsigned()->default(5);
           $table->integer('jifen')->unsigned()->default(10);
           $table->integer('upvoted')->unsigned()->default(0);
           $table->integer('downvoted')->unsigned()->default(0);
           $table->dateTime('lastresponded_at')->default(Carbon\Carbon::now());
           $table->string('introduction')->nullable();//个人简介
           $table->integer('viewed')->unsigned()->default(0);//被访问次数
           $table->string('invitation_token')->nullable();
           $table->string('last_login_ip', 45)->nullable();//上一次访问的ip地址
           $table->dateTime('last_login')->default(Carbon\Carbon::now());//上次登录时间
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
            $table->dropcolumn('shengfan');
            $table->dropcolumn('xianyu');
            $table->dropcolumn('jifen');
            $table->dropcolumn('upvoted');
            $table->dropcolumn('downvoted');
            $table->dropcolumn('lastresponded_at');
            $table->dropcolumn('introduction');
            $table->dropcolumn('viewed');
            $table->dropcolumn('invitation_token');
            $table->dropcolumn('last_login_ip');
            $table->dropcolumn('last_login');
        });
    }
}
