<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\WebStat;

class CountWebStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webstat:count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send webstats to migration table';

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
        $data=[];
        $data['qiandaos']=DB::table('users')->where('lastrewarded_at','>',Carbon::now()->subday(1)->toDateTimeString())->count();
        $data['posts']=DB::table('posts')->where('created_at','>',Carbon::now()->subday(1)->toDateTimeString())->count();
        $data['posts_maintext']=DB::table('posts')->where('created_at','>',Carbon::now()->subday(1)->toDateTimeString())->where('maintext','=','1')->count();
        $data['posts_reply']=DB::table('posts')->where('created_at','>',Carbon::now()->subday(1)->toDateTimeString())->where('maintext','=','0')->count();
        $data['post_comments']=DB::table('post_comments')->where('created_at','>',Carbon::now()->subday(1)->toDateTimeString())->count();
        $data['new_users']=DB::table('users')->where('created_at','>',Carbon::now()->subday(1)->toDateTimeString())->count();
        WebStat::create($data);
    }
}
