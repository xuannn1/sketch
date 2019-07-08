<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait AddTablesTraits{

    public function addTables()//
    {
        echo "start task20 remakeSystemsTables\n";
        Schema::dropIfExists('firewall');
        Schema::create('firewall', function ($table) {
            $table->increments('id');
            $table->string('ip_address', 45)->nullable()->index();//被封禁IP地址
            $table->unsignedInteger('user_id')->default(0)->index();//被封禁用户
            $table->unsignedInteger('admin_id')->default(0)->index();//执行封禁的管理员id
            $table->string('reason', 40)->nullable();//封禁理由
            $table->dateTime('created_at')->nullable();//创建时间
            $table->dateTime('end_at')->nullable();//停止封禁时间
            $table->boolean('is_valid')->default(true);//是否可用
            $table->boolean('is_public')->default(true);//对外公示
        });
        Schema::create('tag_post', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('tag_id')->index();
            $table->unsignedInteger('post_id')->index();
            $table->unique(['post_id', 'tag_id']);
        });
        Schema::create('title_user', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('title_id')->index();
            $table->boolean('is_public')->default(true);//对外公示
            $table->unique(['user_id', 'title_id']);
        });
        Schema::create('titles', function ($table) {
            $table->increments('id');
            $table->string('name',10)->nullable();//头衔名称
            $table->text('description')->nullable();//头衔解释
            $table->unsignedInteger('user_count')->default(0);//多少人获得了这个头衔
        });
        echo "finished remakeSystemsTables\n";

        Schema::table('tag_thread', function($table){
            $table->unique(['tag_id','thread_id']);
        });

        Schema::table('votes', function($table){
            $table->unique(['user_id','votable_type','votable_id','attitude_type']);
        });

        Schema::rename('register_homeworks', 'homework_registrations');
        Schema::table('homework_registrations', function($table){
            $table->renameColumn('updated_at', 'submitted_at');
            echo "modified homework_registrations table\n";
        });
        Schema::table('homeworks', function($table){
            $table->unsignedInteger('registration_thread_id')->default(0)->index();
            $table->unsignedInteger('profile_thread_id')->default(0)->index();
            echo "modified homeworks table\n";
        });
    }
}
