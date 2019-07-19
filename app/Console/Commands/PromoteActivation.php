<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon;
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
        $info = DB::table('user_infos')
        ->join('users','users.id','=','user_infos.user_id')
        ->where('user_infos.email_verified_at','=',null)
        ->where('users.activated','=',1)
        ->orderBy('users.id','desc')
        ->select('users.id')
        ->take(200)
        ->pluck('id')
        ->toArray();

        User::whereIn('id', $info)
        ->where('activated','=',1)
        ->update(['activated'=>0]);
        echo "deactivated some users.\n";

    }
}
