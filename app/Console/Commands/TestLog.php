<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class TestLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testlog:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send a test log to logsystem';

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
        Log::emergency('this is to test if log works.');
    }
}
