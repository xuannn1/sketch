<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('body')->nullable();//题头文字本身
            $table->unsignedInteger('user_id')->index();//提交的人是谁
            $table->boolean('is_anonymous')->default(false);//是否匿名
            $table->string('majia',10)->nullable();//马甲名称
            $table->boolean('not_sad')->default(false);//是否并非丧题头
            $table->boolean('is_approved')->default(false);//是否已经在用
            $table->unsignedInteger('reviewer_id')->default(0);//审核人是谁
            $table->unsignedInteger('xianyu')->default(0);//所获得咸鱼数目
            $table->dateTime('created_at')->nullable();//创建时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}
