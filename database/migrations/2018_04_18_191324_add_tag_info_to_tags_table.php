<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTagInfoToTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->string('tag_explanation')->nullable();//标签说明
            $table->integer('tag_belongs_to')->defalt(0);//同人标签，考虑到会属于某一原著
            $table->integer('label_id')->defalt(0);//考虑到有些同人原著应放置到某一大类下
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropcolumn('tag_explanation');
            $table->dropcolumn('tag_belongs_to');
            $table->dropcolumn('label_id');
        });
    }
}
