<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use DB;

class ClearRecords extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'data:clearRecords';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'to recalculate records';

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
        $time = Carbon::now()->subDay(1)->toDateTimeString();
        DB::statement('
        DELETE h1
        FROM historical_users_activities h1
        INNER JOIN
        historical_users_activities h2
        WHERE
        h1.id < h2.id AND h1.user_id = h2.user_id AND h1.ip = h2.ip AND h1.created_at > '.'"'.$time.'"'
    );
    }
}
