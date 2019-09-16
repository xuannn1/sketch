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
        for($i=0;$i<=10;$i++){
            $user_count = DB::table('users')->where('level',$i)->count();
            $ham_average = DB::table('users')
            ->join('user_infos','user_infos.user_id','=','users.id')
            ->where('users.level',$i)
            ->avg('user_infos.ham');
            $user_with_book = DB::table('users')
            ->join('threads','threads.user_id','=','users.id')
            ->where('users.level',$i)
            ->where('threads.deleted_at','=',null)
            ->where('threads.is_public','=',1)
            ->where('threads.channel_id','<=',2)
            ->where('threads.total_char','>',500)
            ->count();
            echo 'level = '.$i.'|'.'user count = '.$user_count.'|'.'ham_avg = '.$ham_average.'|'.'user with books = '.$user_with_book."\n";
        }

    }
}
