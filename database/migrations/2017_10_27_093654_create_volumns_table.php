<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volumns', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('volumn_order')->unsigned()->default(0);//卷order，暗示在整个书中的排列顺序（从小到大）
            $table->integer('book_id')->unsigned()->index();//对应书本id
            $table->string('title')->nullable();//卷名
            $table->string('body')->nullable();//本卷简介
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
        Schema::dropIfExists('volumns');
    }
}
