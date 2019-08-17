<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use DB;

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
        $password_records = DB::table('password_resets')->where('created_at','>','2019-08-14 24:00:00')->get();
        $password_records = $password_records->map(function($x){ return (array) $x; })->toArray();
        DB::table('password_resets_2')->insert($password_records);

    }
}
