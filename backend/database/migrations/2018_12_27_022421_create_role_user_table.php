<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->string('role', 20);//身份标志
            $table->json('options')->nullable();//如果是对应的channel，或者homework，会有一个id注明可以弄哪些
            $table->primary(['user_id', 'role']);
            $table->string('reason')->nullable();//授权理由
            $table->dateTime('created_at')->nullable();//创建时间
            $table->dateTime('end_at')->nullable();//停止时间
            $table->boolean('is_valid')->default(true);//是否可用
            $table->boolean('is_public')->default(false);//对外公示
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
    }
}
