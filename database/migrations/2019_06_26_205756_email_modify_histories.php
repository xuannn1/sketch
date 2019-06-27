<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailModifyHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_modify_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('old-email')->index();//老邮箱
            $table->string('new-email')->index();//新邮箱
            $table->string('ip_address', 45)->index();// 修改资料时IP地址
            $table->integer('user_id')->unsigned()->index();//用户id记录
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_modify_histories');
    }
}
