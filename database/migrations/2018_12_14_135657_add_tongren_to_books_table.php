<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTongrenToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('tongren_yuanzhu')->nullable();//同人原著信息
            $table->string('tongren_cp')->nullable();//同人cp信息
            //$table->integer('tongren_yuanzhu_tag_id')->default(0);//同人原著tagid
            //$table->integer('tongren_cp_tag_id')->default(0);//同人cptagid
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropcolumn('tongren_yuanzhu');
            $table->dropcolumn('tongren_cp');
            //$table->dropcolumn('tongren_yuanzhu_tag_id');
            //$table->dropcolumn('tongren_cp_tag_id');
        });
    }
}
