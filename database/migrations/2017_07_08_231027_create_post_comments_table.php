<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_comments', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('post_id')->index();
           $table->integer('user_id');
           $table->integer('up_voted')->unsigned()->default(0);//顶
           $table->integer('down_voted')->unsigned()->default(0);//踩
           $table->integer('funny')->unsigned()->default(0);//搞笑
           $table->integer('fold')->unsigned()->default(0);//几次被人说要折叠
           $table->boolean('fold_state')->default(false);//是否折叠
           $table->boolean('anonymous')->default(false);//匿名
           $table->string('majia', 10)->nullable();//马甲
           $table->string('body')->nullable();//正文
           $table->boolean('hide')->default(false);//匿名
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
        Schema::dropIfExists('post_comments');
    }
}
