<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('thread_id');//被推荐的书籍或讨论帖
            $table->string('brief')->nullable();//简介
            $table->text('body')->nullable();//推荐正文
            $table->string('type',10)->nullable();//推荐类型：long，short，topic：长推，短推，专题。。。
            $table->boolean('is_public')->default(false);//是否公开
            $table->boolean('is_past')->default(false);//是否属于往期推荐
            $table->unsignedInteger('views')->default(0);//因为它而点击进入thread的数量
            $table->dateTime('created_at')->nullable();//创建时间
            $table->unique(['thread_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recommendations');
    }
}
