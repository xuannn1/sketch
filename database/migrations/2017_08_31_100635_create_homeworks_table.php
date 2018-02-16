<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->increments('id');
            //$table->string('theme')->index();//主题
            // $table->text('requirement')->nullable();//作业具体要求
            // $table->tinyInteger('registration_points')->unsigned()->default(5);//报名所需丧点
            // $table->dateTime('registration_deadline')->default(Carbon\Carbon::now());//在xx日之前必须完成报名
            // $table->dateTime('submission_deadline')->default(Carbon\Carbon::now());//在xx日之前必须完成作业
            // $table->dateTime('review_deadline')->default(Carbon\Carbon::now());//在xx日之前必须完成批改
            $table->boolean('active')->default(true);
            //$table->softDeletes();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homeworks');
    }
}
