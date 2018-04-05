<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoolResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pool_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned()->index();//对应的post是哪一个
            $table->integer('pool_id')->unsigned()->index();//对应的投票是哪一个
            for ($i = 0; $i <= 10; $i++) {
                $table->integer('option_votes'.$i);//第i个分支选项有几票
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pool_responses');
    }
}
