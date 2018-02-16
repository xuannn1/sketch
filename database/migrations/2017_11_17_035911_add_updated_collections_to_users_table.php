<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatedCollectionsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('collection_threads_updated')->unsigned()->default(0);//收藏的主题贴更新提醒
            $table->integer('collection_books_updated')->unsigned()->default(0);//收藏的文章更新提醒
            $table->integer('collection_statuses_updated')->unsigned()->default(0);//收藏的用户动态更新提醒
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
            $table->dropcolumn('collection_threads_updated');//
            $table->dropcolumn('collection_books_updated');//
            $table->dropcolumn('collection_statuses_updated');//
        });
    }
}
