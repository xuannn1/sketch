<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSexualOrientationToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('books', function (Blueprint $table) {
          $table->tinyInteger('sexual_orientation')->default(0);//0:未知，1:BL，2:GL，3:BG，4:gb，5:混合性向，6:无CP，7:其他性向
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
          $table->dropcolumn('sexual_orientation');
      });
    }
}
