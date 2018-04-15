<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {//投票
            $table->increments('id');
            $table->integer('post_id')->unsigned()->index();//对应的post是哪一个
            $table->tinyInteger('option_number')->default(0);//一共可以选择几项
            for ($i = 0; $i <= 10; $i++) {
                $table->string('option_name'.$i)->nullable();//第i个分支选项的义肢
                $table->integer('option_votes'.$i);//第i个分支选项有几票
            }
            $table->integer('reward_jifen');//奖励多少积分
            $table->integer('reward_jifen_distribution')->default(0);//奖励均分给几个人，0为随机分发奖励
            $table->integer('reward_sangdian');//奖励多少丧点
            $table->integer('reward_sangdian_distribution')->default(0);//奖励均分给几个人，0为随机分发奖励
            $table->dateTime('end_time')->default(Carbon\Carbon::now())->index();//投票截止日期
            $table->softDeletes();
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
        Schema::dropIfExists('polls');
    }
}
