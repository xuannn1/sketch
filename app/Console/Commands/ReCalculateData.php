<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use DB;

class ReCalculateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:recalculation';

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

        DB::table('threads')
        ->where('total_char','=',0)
        ->whereIn('channel_id',[1,2])
        ->where('deleted_at','=',null)
        ->where('created_at','<',Carbon::now()->subDay(1)->toDateTimeString())
        ->where('is_public','=',1)
        ->update(['is_public'=>0]);

        DB::table('users')
        ->join('user_infos','user_infos.user_id','=','users.id')
        ->where('user_infos.no_logging_until', '<', Carbon::now()->toDateTimeString())
        ->update(['users.no_logging'=>0]);

        DB::table('users')
        ->join('user_infos','user_infos.user_id','=','users.id')
        ->where('user_infos.no_posting_until', '<', Carbon::now()->toDateTimeString())
        ->update(['users.no_posting'=>0]);

        DB::statement('
            update user_infos
            set follower_count =
            (select count(id) from followers
            where followers.user_id = user_infos.user_id)
        ');

        DB::statement('
            update user_infos
            set following_count =
            (select count(id) from followers
            where followers.follower_id = user_infos.user_id)
        ');

        DB::statement('
            update tags
            set thread_count =
            (select count(threads.id) from threads, tag_thread
            where tag_thread.tag_id = tags.id and tag_thread.thread_id = threads.id and threads.deleted_at is null)
        ');

        DB::statement('
            update user_infos
            set invitee_count =
            (select count(id) from user_infos as infos
            where user_infos.user_id = infos.invitor_id)
        ');


        Log::emergency('did recalculate Database');
    }
}
