<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollectionListToCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->integer('collection_list_id')->unsigned()->default(0);//属于哪个收藏夹
            $table->integer('item_id')->unsigned()->default(0);//什么东西
            $table->string('brief')->nullable();//简要介绍
            $table->text('body')->nullable();//详细介绍
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropcolumn('collection_list_id');
            $table->dropcolumn('item_id');
            $table->dropcolumn('brief');
            $table->dropcolumn('body');
        });
    }
}
