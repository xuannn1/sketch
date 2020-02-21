<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_notices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->index(); // 系统消息标题
            $table->text('body'); // 系统消息内容
            $table->integer('user_id')->default(0)->index(); // 发送者
            $table->dateTime('created_at')->nullable()->index();
            $table->dateTime('edited_at')->nullable()->index();
            $table->dateTime('deleted_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_notices');
    }
}
