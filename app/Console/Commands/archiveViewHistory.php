<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use DB;
use Log;

class archiveViewHistory extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'data:archiveViewHistory';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'archive activity records and view history records';

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        $user_views = DB::table('today_users_views')->select('user_id','thread_id','created_at')->get();

        $user_views = $user_views->map(function ($user_view) {
            return (array)$user_view;
        })->toArray();

        foreach (array_chunk($user_views, 2000) as $t){
            DB::table('historical_users_views')->insert($t);
        }

        DB::table('today_users_views')->delete();
    }
}
