<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManagementToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->integer('group')->default(10);//用户组 新手10-进阶11（能发言）-高级20(能去文章讨论区)-管理员20（能去管理区）
           $table->dateTime('no_posting')->default(Carbon\Carbon::now());//被禁言到什么时间
           $table->dateTime('no_logging')->default(Carbon\Carbon::now());//被禁止登陆到什么时间
           $table->integer('user_level')->default('0');//用户等级
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
            $table->dropcolumn('group');
            $table->dropcolumn('no_posting');
            $table->dropcolumn('no_logging');
            $table->dropcolumn('user_level');
        });
    }
}
