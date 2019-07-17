<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserIntrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_intros', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->primary();
            $table->text('body')->nullable();
            $table->dateTime('edited_at')->nullable();//简介修改于
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_intros');
    }
}
