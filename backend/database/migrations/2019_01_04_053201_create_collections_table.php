<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('post_id')->default(0);
            $table->string('collectable_type',20)->nullable();//被收藏物品类型，如thread，post，recommendation，collection_list
            $table->unsignedInteger('collectable_id')->default(0);//被收藏物品id
            $table->unsignedInteger('list_id')->default(0);//丛属的收藏单(对应的thread)id，也可以是0，意味着是这人默认的隐藏不公开收藏单。
            $table->boolean('keep_updated')->default(true);//是否发送更新提示
            $table->boolean('is_updated')->default(false);//是否存在新消息/更新的提示

            $table->primary(['user_id', 'list_id', 'collectable_type', 'collectable_id'], 'userid_listid_type_id_primary');
            $table->index(['collectable_type','collectable_id']);
            $table->index('list_id');
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
    }
}
