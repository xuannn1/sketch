<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnonymousToCollectionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collection_lists', function (Blueprint $table) {
            $table->boolean('anonymous')->default(false);//匿名
            $table->string('majia', 10)->nullable();//马甲
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collection_lists', function (Blueprint $table) {
            $table->dropcolumn('anonymous');
            $table->dropcolumn('majia');
        });
    }
}
