<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ModifyAdminRecordsTraits{

    public function modifyAdminRecords()
    {
        if(!Schema::hasColumn('administrations', 'record')){
            Schema::table('administrations', function($table){
                $table->string('record', 50)->nullable();
                $table->string('administratable_type', 10)->nullable()->index();
                $table->unsignedInteger('administratable_id')->default(0)->index();
                $table->index('item_id');
                $table->index('operation');
                echo "echo added new columns to administrations table.\n";
            });
        }
        DB::table('administrations')
        ->update([
            'reason' => DB::raw('substring(reason,1,50)'),
        ]);
        echo"truncated all admin records\n";

        DB::table('administrations')
        ->join('threads','administrations.item_id','=','threads.id')
        ->whereIn('administrations.operation',[1,2,3,4,5,6,9,15,16,40,41,42,43,44,45])
        ->update([
            'administrations.record' => DB::raw('substring(threads.title,1,50)'),
            'administrations.administratable_type' => 'thread',
            'administrations.administratable_id' => DB::raw('administrations.item_id'),
        ]);
        echo"updated thread admin records\n";

        DB::table('administrations')
        ->join('posts','administrations.item_id','=','posts.id')
        ->whereIn('administrations.operation',[7,10,11,12,30])
        ->update([
            'administrations.record' => DB::raw('substring(posts.brief,1,50)'),
            'administrations.administratable_type' => 'post',
            'administrations.administratable_id' => DB::raw('administrations.item_id'),
        ]);
        echo"updated post admin records\n";

        DB::table('administrations')
        ->join('post_comments','administrations.item_id','=','post_comments.id')
        ->whereIn('administrations.operation',[8,31])
        ->update([
            'administrations.record' => DB::raw('substring(post_comments.body,1,50)'),
        ]);

        DB::table('administrations')
        ->join('statuses','administrations.item_id','=','statuses.id')
        ->where('administrations.operation','=',17)
        ->update([
            'administrations.record' => DB::raw('substring(statuses.content,1,50)'),
            'administrations.administratable_type' => 'status',
            'administrations.administratable_id' => DB::raw('administrations.item_id'),
        ]);
        echo"updated status admin records\n";

        DB::table('administrations')
        ->join('users','administrations.item_id','=','users.id')
        ->whereIn('administrations.operation',[13,14,18,19,20,50])
        ->update([
            'administrations.record' => DB::raw('users.name'),
            'administrations.administratable_type' => 'user',
            'administrations.administratable_id' => DB::raw('administrations.item_id'),
        ]);
        echo"updated user admin records\n";
    }
}
