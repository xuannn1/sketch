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
            $table->string('tag_name')->nullable();
            $table->integer('tag_group')->default(0);//普通tag：0/边缘tag：5/同人原著tag:10/同人cptag:20/同人大类tag：25/编辑推荐类tag：30/
            $table->integer('tag_info')->default(0);
            $table->string('tag_explanation')->nullable();
            $table->unsignedInteger('belongs_to_tag_id')->default(0);//用于同人CP寻找同人原著，同人原著寻找同人作品其他分类
            $table->integer('tagged_books')->default(0);
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
