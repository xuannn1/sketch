<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use Cache;
use App\Models\User;

class PromoteActivation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activation:promote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'modify last 300 user so that they have to activate email.';

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
        User::where('activation_token','<>', null)
        ->where('activated','=',1)
        ->orderBy('created_at','desc')
        ->limit(100)->update(['activated' => 0]);
    }
}
