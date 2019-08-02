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
        // //重新统计每一本书里准确的回帖数目
        // DB::statement('
        //     update threads
        //     set responded =
        //     (select count(id) from posts
        //     where threads.id = posts.thread_id and posts.deleted_at is null and threads.post_id <> posts.id and posts.maintext = 0)
        // ');
        // //重新统计每一post下准确的回帖数目
                // //统计总积分：咸鱼+剩饭+点击+回复+下载+收藏
                // DB::table('threads')
                // ->update(['jifen'=>DB::raw('xianyu * 2000 + shengfan *100 + viewed*2 + responded * 100 + downloaded * 2000 + collection * 2000')]);
                // //对非边缘文进行额外奖励，因为边缘文积分太高了
                // DB::table('threads')
                // ->where('bianyuan','=',0)
                // ->update(['jifen'=>DB::raw('jifen*3 +2000')]);
                // //计算均摊字数之后的一个分值
                // DB::table('threads')
                // ->join('books','threads.book_id','=','books.id')
                // ->update(['threads.jifen'=>DB::raw('threads.jifen + books.total_char'), 'books.weighted_jifen'=>DB::raw('(threads.jifen * 1000) DIV (books.total_char + 10000)')]);


        // //隐藏没有章节的文章

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
            (select count(id) from tag_thread
            where tag_thread.tag_id = tags.id)
        ');


        Log::emergency('did recalculate Database');
    }
}
