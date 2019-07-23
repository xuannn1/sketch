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
    protected $description = 'to recalculate jifen for threads, weighted_jifen for books';

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
        DB::table('votes')
        ->join('posts','posts.id','=','votes.votable_id')
        ->where('votes.receiver_id','=',0)
        ->where('votes.votable_type','=', 'post')
        ->update(['votes.receiver_id'=>DB::raw('posts.user_id')]);
    }
}
