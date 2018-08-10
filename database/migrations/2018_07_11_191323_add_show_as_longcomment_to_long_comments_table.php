<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShowAsLongcommentToLongCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('long_comments', function (Blueprint $table) {
            $table->boolean('reviewed')->default(0);//是否已经审核过
            $table->boolean('approved')->default(0);//是否公示为长评
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('long_comments', function (Blueprint $table) {
            $table->dropcolumn('reviewed');
            $table->dropcolumn('approved');
        });
    }
}
