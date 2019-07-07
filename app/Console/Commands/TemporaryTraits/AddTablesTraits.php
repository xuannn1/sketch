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
            $table->string('ip_address', 45)->index();//被封禁IP地址
            $table->unsignedInteger('user_id')->default(0)->index();//执行封禁的管理员id
            $table->string('reason')->nullable();//封禁理由
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
        });
        Schema::create('titles', function ($table) {
            $table->increments('id');
            $table->string('name',10)->nullable();//头衔名称
            $table->text('description')->nullable();//头衔解释
            $table->unsignedInteger('user_count')->default(0);//多少人获得了这个头衔
        });
        echo "finished remakeSystemsTables\n";
    }

}
