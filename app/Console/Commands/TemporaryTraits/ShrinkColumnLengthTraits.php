<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ShrinkColumnLengthTraits{


    public function shrinkColumnLength()//
    {
        DB::table('posts')->update([
            'title' => DB::raw('substring(title,1,10)'),
            'brief' => DB::raw('substring(brief,1,20)'),
            'reply_to_brief' => DB::raw('substring(reply_to_brief,1,20)'),
        ]);
        echo "shrinked posts\n";

        // Schema::table('posts', function (Blueprint $table) {
        //     $table->string('title', 10)->change();
        //     $table->string('brief', 20)->change();
        //     $table->string('reply_to_brief', 20)->change();
        //     $table->string('creation_ip', 45)->change();
        // });
        // echo "updated posts table\n";

        DB::table('threads')->update([
            'title' => DB::raw('substring(brief,1,30)'),
            'brief' => DB::raw('substring(brief,1,50)'),
        ]);
        echo "shrinked threads\n";

        // Schema::table('threads', function (Blueprint $table) {
        //     $table->string('title', 30)->change();
        //     $table->string('brief', 50)->change();
        // });
        // echo "updated threads table\n";

        DB::table('statuses')->update([
            'brief' => DB::raw('substring(brief,1,20)'),
        ]);
        echo "shrinked statuses\n";

        // Schema::table('statuses', function (Blueprint $table) {
        //     $table->string('brief', 20)->change();
        // });
        // echo "updated statuses table\n";
    }

}
