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
        DB::statement('
            update user_infos as u1, user_infos as u2
            set u1.invitee_count =
            (select count(u2.user_id)
            where u2.invitor_id>0 and u1.user_id = u2.invitor_id)
        ');

    }
}
