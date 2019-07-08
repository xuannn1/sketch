<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ModifyQuoteNStatusTraits{

    public function modifyQuoteNStatus()
    {
        $this->modifyQuote();
        $this->shrinkStatus();
        $this->modifyStatus();
    }

    public function modifyQuote()
    {
        echo "task update quotes table\n";
        if (Schema::hasColumn('quotes', 'quote')){
            Schema::table('quotes', function($table){
                $table->renameColumn('quote', 'body');
                $table->unsignedInteger('reviewer_id')->default(0);
                $table->dropColumn(['updated_at']);
                echo "echo updated quotes table.\n";
            });
        }
        $user = \App\Models\User::where('name','=','废文网大内总管')->first();
        if($user){
            DB::table('quotes')
            ->where('approved','=',1)
            ->update([
                'reviewer_id' => $user->id
            ]);
        }
    }

    public function shrinkStatus()
    {
        echo "start shrink statuses\n";
        DB::table('statuses')->where('content','like','%]更新了《%')
        ->delete();
        DB::table('statuses')->where('content','like','%<p>更新了[url=%')
        ->delete();
        $statuses = DB::table('statuses')->where('content','like','%<p>%')
        ->get();
        foreach($statuses as $status){
            $string = $status->content;
            $string = str_replace("<p>", "", $string);
            $string = str_replace("</p>", "", $string);
            DB::table('statuses')->where('id','=',$status->id)
            ->update([
                'content' => $string
            ]);
        }
        echo "shrinked statuses \n";

    }

    public function modifyStatus()
    {
        echo "start task updateStatuses\n";
        if (Schema::hasColumn('statuses', 'content')){
            Schema::table('statuses', function($table){
                $table->renameColumn('content', 'brief');
                $table->text('body')->nullable();
                $table->string('attachable_type',10)->nullable();
                $table->unsignedInteger('attachable_id')->default(0);
                $table->unsignedInteger('reply_to_id')->default(0)->index();
                $table->boolean('no_reply')->default(false);
                $table->unsignedInteger('reply_count')->default(0);
                $table->unsignedInteger('last_reply_id')->default(0)->index();
                $table->unsignedInteger('forward_count')->default(0);
                $table->unsignedInteger('upvote_count')->default(0);
                $table->string('creation_ip', 45)->nullable();//创建时IP地址
                $table->dropColumn(['updated_at']);
                echo "echo updated statuses table.\n";
            });
        }
        DB::table('statuses')->update([
            'body' => DB::raw('brief')
        ]);
        echo "finished updateStatuses\n";
    }

}
