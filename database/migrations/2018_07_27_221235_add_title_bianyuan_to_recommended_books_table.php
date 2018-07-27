<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleBianyuanToRecommendedBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recommend_books', function (Blueprint $table) {
            $table->string('title')->nullable();//推荐图书的标题
            $table->boolean('bianyuan')->default(false);//是否边缘类型
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recommend_books', function (Blueprint $table) {
            $table->dropcolumn('title');
            $table->dropcolumn('bianyuan');
        });
    }
}
