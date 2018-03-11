<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowDownloadToThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->boolean('download_as_thread')->default(1);//是否允许以聊天记录形式下载全文
            $table->boolean('download_as_book')->default(1);//是否允许以脱水形式下载图书
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->dropcolumn('download_as_thread');
            $table->dropcolumn('download_as_book');
        });
    }
}
