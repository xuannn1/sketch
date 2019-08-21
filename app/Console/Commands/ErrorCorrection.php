<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use DB;
use Log;

class ErrorCorrection extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'data:errorCorrection';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'a temporaral commander for things';

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
        DB::table('users')
        ->join('user_infos','user_infos.user_id','=','users.id')
        ->where('users.no_logging','=',1)
        ->where('user_infos.no_logging_until', '=', null)
        ->update(['users.no_logging'=>0]);
    }
}
