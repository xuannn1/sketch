<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ModifyAdminRecordsTraits{

    public function modifyAdminRecords()
    {
        if(!Schema::hasColumn('administrations', 'record')){
            Schema::table('administrations', function($table){
                $table->string('record', 40)->nullable();
                $table->string('administratable_type', 10)->nullable()->index();
                $table->unsignedInteger('administratable_id')->default(0)->index();
                echo "echo added new columns to tags table.\n";
            });
        }
        DB::table('administrations')
        ->update([
            'reason' => DB::raw('substring(reason,1,30)'),
        ]);
        echo"truncated all admin records\n";

        DB::table('administrations')
        ->join('threads','administrations.item_id','=','threads.id')
        ->whereIn('administrations.operation',[1,2,3,4,5,6,9,15,16,40,41,42,43,44,45])
        ->update([
            'administrations.record' => DB::raw('substring(threads.title,1,20)'),
            'administrations.administratable_type' => 'thread',
            'administrations.administratable_id' => DB::raw('administrations.item_id'),
        ]);
        echo"updated thread admin records\n";

        DB::table('administrations')
        ->join('posts','administrations.item_id','=','posts.id')
        ->whereIn('administrations.operation',[7,10,11,12,30])
        ->update([
            'administrations.record' => DB::raw('substring(posts.brief,1,20)'),
            'administrations.administratable_type' => 'post',
            'administrations.administratable_id' => DB::raw('administrations.item_id'),
        ]);
        echo"updated post admin records\n";

        if(Schema::hasColumn('posts', 'postcomment_id')){
            DB::table('administrations')
            ->join('posts','administrations.item_id','=','posts.postcomment_id')
            ->where('administrations.operation','=',8)
            ->update([
                'administrations.record' => DB::raw('substring(posts.brief,1,20)'),
                'administrations.administratable_type' => 'post',
                'administrations.administratable_id' => DB::raw('posts.id'),
                'administrations.operation' => 7,
            ]);

            DB::table('administrations')
            ->join('posts','administrations.item_id','=','posts.postcomment_id')
            ->where('administrations.operation','=',31)
            ->update([
                'administrations.record' => DB::raw('substring(posts.brief,1,20)'),
                'administrations.administratable_type' => 'post',
                'administrations.administratable_id' => DB::raw('posts.id'),
                'administrations.operation' => 30,
            ]);

            echo"updated postcomments administration records\n";
        }else{
            echo"ERROR:have not transferred postcomments to posts\n";
        }

        DB::table('administrations')
        ->join('statuses','administrations.item_id','=','statuses.id')
        ->where('administrations.operation','=',17)
        ->update([
            'administrations.record' => DB::raw('substring(statuses.content,1,20)'),
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
