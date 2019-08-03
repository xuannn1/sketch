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
            DELETE c1 FROM collections c1
            INNER JOIN
            collections c2
            WHERE
            c1.id > c2.id AND c1.user_id = c2.user_id and c1.thread_id = c2.thread_id;
        ');

    }
}
