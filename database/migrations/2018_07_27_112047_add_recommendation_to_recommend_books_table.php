<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecommendationToRecommendBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recommend_books', function (Blueprint $table) {
            $table->text('recommendation')->nullable();//推荐语
            $table->boolean('past')->default(false);//是否历史数据
            $table->boolean('long')->default(false);//是否长评
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
            $table->dropcolumn('recommendation');
            $table->dropcolumn('past');
            $table->dropcolumn('long');
        });
    }
}
