<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait modifyActivityTableTraits{

    public function modifyActivityTable()
    {
        if(!Schema::hasColumn('activities', 'item_type')){
            Schema::table('activities', function($table){
                $table->string('item_type', 10)->nullable();
            });
            echo "echo added new columns to activities table.\n";
        }
        DB::table('activities')
        ->join('posts','posts.id','=','activities.item_id')
        ->whereIn('activities.type',[1,2])
        ->update([
            'activities.item_type' => 'post',
        ]);
        echo "echo updated reply activities.\n";

        DB::table('activities')
        ->join('posts','posts.postcomment_id','=','activities.item_id')
        ->where('activities.type','=',3)
        ->update([
            'activities.item_type' => 'post',
            'activities.item_id' => DB::raw('posts.id'),
            'activities.type' => 2,
        ]);
        echo "echo updated previously postcomment activities.\n";

        DB::table('activities')
        ->join('votes','votes.record_id','=','activities.item_id')
        ->where('activities.type','=',5)
        ->update([
            'activities.item_type' => 'vote',
            'activities.item_id' => DB::raw('votes.id'),
        ]);
        echo "echo updated upvote activities.\n";

        DB::table('activities')
        ->join('questions','questions.id','=','activities.item_id')
        ->where('activities.type','=',6)
        ->update([
            'activities.item_type' => 'post',
            'activities.item_id' => DB::raw('questions.post_id'),
            'activities.type' => 1,
        ]);
        echo "echo updated previously question activities.\n";
    }
}
