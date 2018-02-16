<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoreplyToThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('threads', function (Blueprint $table) {
          $table->boolean('noreply')->default(false);//请勿跟帖
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
           $table->dropcolumn('noreply');
      });
    }
}
