<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->unsignedInteger('post_id')->primary();
            $table->unsignedInteger('thread_id')->default(0)->index();//以后也允许登记外站书籍
            $table->boolean('recommend')->default(true);//是否对外推荐
            $table->boolean('long')->default(false);//是否属于长评，字数超过几百字xx算长评
            $table->boolean('author_disapprove')->default(false);//作者不同意展示
            $table->boolean('editor_recommend')->default(false);//编辑推荐
            $table->tinyInteger('rating')->default(0);//评分，可以为零（不打分）
            $table->unsignedInteger('redirects')->default(0);//看完文评之后前进看书的比例
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
