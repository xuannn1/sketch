<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('body')->nullable();
            $table->string('attachable_type', 10)->nullable();//附件类型：status(转发当条状态), chapter, book, thread, post..., picture
            $table->unsignedInteger('attachable_id')->default(0);//附件id
            $table->unsignedInteger('reply_to_status_id')->default(0)->index();//回复的status是哪个
            $table->dateTime('created_at')->nullable();//创建时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
