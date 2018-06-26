<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollectionListsUpdatedToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('collection_lists_updated')->unsigned()->default(0);//收藏表单的更新提醒
            $table->tinyInteger('collection_list_limit')->unsigned()->default(0);//收藏单数目限制
            $table->bigInteger('clicks')->unsigned()->default(0);//点击统计数
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropcolumn('collection_lists_updated');
            $table->dropcolumn('collection_list_limit');
        });
    }
}
