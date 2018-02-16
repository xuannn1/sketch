<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tags', function (Blueprint $table) {
           $table->increments('id');
           $table->string('tagname', 10);//标签名称
           $table->tinyInteger('tag_group')->default(0);
           $table->dateTime('lastresponded_at')->default(Carbon\Carbon::now())->index();
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
      Schema::dropIfExists('tags');
    }
}
