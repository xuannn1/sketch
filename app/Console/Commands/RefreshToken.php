<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon;
use Cache;
use Log;

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
    protected $description = 'refresh invitation token to senior users with books';

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
        ->join('user_infos','users.id','=','user_infos.user_id')
        ->join('threads','threads.user_id','=','users.id')
        ->where('threads.deleted_at','=',null)
        ->where('threads.is_public','=',1)
        ->where('threads.total_char','>',5000)
        ->where('users.level','>=',4)
        ->where('users.quiz_level','>=',2)
        ->where('user_infos.token_limit','<',5)
        ->update(['user_infos.token_limit'=>DB::raw('user_infos.token_limit+1')]);
    }
}
