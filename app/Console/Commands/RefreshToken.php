<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class RefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh invitation token times';

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
        DB::table('invitation_tokens')
        ->where('refresh_times','>',0)
        ->update(['invitation_times' => DB::raw('refresh_times')]);
    }
}
