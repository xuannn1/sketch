<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->unsignedInteger('post_id')->primary();
            $table->unsignedInteger('volumn_id')->default(0);//分卷号
            $table->integer('order_by')->default(0);//排序号码
            $table->string('warning')->nullable();//文前预警
            $table->text('annotation')->nullable();//章节备注
            $table->unsignedInteger('previous_id')->default(0);//前一章的章节序数（post_id）
            $table->unsignedInteger('next_id')->default(0);//后一章的章节序数（post_id）
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapters');
    }
}
