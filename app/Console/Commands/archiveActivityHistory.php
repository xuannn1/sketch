<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use DB;
use Log;

class archiveActivityHistory extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'data:archiveActivityHistory';

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
        $user_activities = DB::table('today_users_activities')->select('user_id','ip','created_at')->get();

        $user_activities = $user_activities->map(function ($user_activity) {
            return (array)$user_activity;
        })->toArray();

        foreach (array_chunk($user_activities, 2000) as $t){
            DB::table('historical_users_activities')->insert($t);
        }

        DB::table('today_users_activities')->delete();
    }
}
