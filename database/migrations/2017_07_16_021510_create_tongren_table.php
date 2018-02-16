<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTongrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tongrens', function (Blueprint $table) {
         $table->increments('id');
         $table->integer('book_id')->unsigned()->index();
         $table->string('tongren_yuanzhu')->nullable();//同人信息
         $table->string('tongren_cp')->nullable();//同人原著
         $table->integer('tongren_yuanzhu_tag_id')->unsigned()->default(0)->index();
         $table->integer('tongren_CP_tag_id')->unsigned()->default(0)->index();
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
        Schema::dropIfExists('tongrens');
    }
}
