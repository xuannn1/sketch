<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {//讨论区回帖
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('thread_id')->unsigned()->index();
            $table->integer('up_voted')->unsigned()->default(0);//顶
            $table->integer('down_voted')->unsigned()->default(0);//踩
            $table->integer('funny')->unsigned()->default(0);//搞笑
            $table->integer('fold')->unsigned()->default(0);//几次被人说要折叠
            $table->boolean('fold_state')->default(false);//是否折叠
            $table->boolean('anonymous')->default(false);//匿名
            $table->string('majia', 10)->nullable();//马甲
            $table->text('body')->nullable();//正文
            $table->dateTime('lastresponded_at')->default(Carbon::now())->index();
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
        Schema::dropIfExists('posts');
    }
}
