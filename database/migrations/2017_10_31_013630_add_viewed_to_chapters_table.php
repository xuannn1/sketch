<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddViewedToChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapters', function (Blueprint $table) {
           $table->integer('characters')->unsigned()->default(0);//字数
           $table->integer('viewed')->unsigned()->default(0);//点击浏览次数
           $table->integer('responded')->unsigned()->default(0);//各类回应统计（点赞，回帖，点评）
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropcolumn('characters');//
            $table->dropcolumn('viewed');//
            $table->dropcolumn('responded');//
        });
    }
}
