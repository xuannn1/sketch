<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('thread_id')->unsigned()->index();//属于哪个线
            $table->boolean('original')->default(true);//原创or同人
            $table->tinyInteger('book_status')->default(0);//连载进度：连载-1，完结-2，暂停-3
            $table->tinyInteger('book_length')->default(0);//篇幅：短篇-1，中篇-2，长篇-3
            $table->dateTime('lastresponded_at')->default(Carbon::now())->index();
            $table->dateTime('lastaddedchapter_at')->default(Carbon::now())->index();
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
        Schema::dropIfExists('books');
    }
}
