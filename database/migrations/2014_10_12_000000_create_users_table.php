<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {//用户
            $table->increments('id');
            $table->string('name')->unique()->index();//用户名/笔名
            $table->string('email')->unique()->index();//注册邮箱
            $table->string('password');//密码
            $table->boolean('activated')->default(false);//是否激活
            $table->string('activation_token')->nullable();//激活码
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
