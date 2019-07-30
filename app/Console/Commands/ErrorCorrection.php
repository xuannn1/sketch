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
        // DB::statement('
        //     update user_infos
        //     set token_limit =
        //     (select count(id) from threads
        //     where threads.user_id = user_infos.user_id and threads.channel_id<3 and threads.deleted_at is null)
        // ');

        DB::table('user_infos')
        ->join('users','users.id','=','user_infos.user_id')
        ->where('users.level','<',6)
        ->update(['user_infos.token_limit'=>0]);

        DB::table('user_infos')
        ->join('users','users.id','=','user_infos.user_id')
        ->where('users.quiz_level','<',3)
        ->update(['user_infos.token_limit'=>0]);

        DB::table('user_infos')
        ->where('token_limit','<',3)
        ->update(['token_limit'=>0]);

        DB::table('user_infos')
        ->where('token_limit','>',3)
        ->update(['token_limit'=>3]);
    }
}
