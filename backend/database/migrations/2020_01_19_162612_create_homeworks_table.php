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
            $table->string('title', 30)->nullable()->index();//作业名称，如“第几次作业”
            $table->string('topic', 30)->nullable()->index();//作业主题，如“瓶中信”
            $table->integer('level')->default(0)->index();
            $table->integer('ham_base')->default(0);//作业奖励的火腿基础
            $table->boolean('is_active')->default(true)->index();//是否仍是进行中的作业
            $table->boolean('allow_watch')->default(true)->index();
            $table->dateTime('registration_on')->nullable()->index();
            $table->integer('worker_registration_limit')->default(0)->index();
            $table->integer('critic_registration_limit')->default(0)->index();
            $table->dateTime('created_at')->nullable()->index();
            $table->dateTime('end_at')->nullable()->index();
            $table->unsignedInteger('registration_thread_id')->default(0)->index();
            $table->unsignedInteger('profile_thread_id')->default(0)->index();
            $table->unsignedInteger('summary_thread_id')->default(0)->index();
            $table->unsignedInteger('purchase_count')->default(0)->index();
            $table->unsignedInteger('worker_count')->default(0)->index();
            $table->unsignedInteger('critic_count')->default(0)->index();
            $table->unsignedInteger('finished_work_count')->default(0)->index();
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
        Schema::dropIfExists('homeworks');
    }
}
