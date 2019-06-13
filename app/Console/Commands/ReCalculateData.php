<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
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
        //重新统计每一本书里准确的回帖数目
        DB::statement('
            update threads
            set responded =
            (select count(id) from posts
            where threads.id = posts.thread_id and posts.deleted_at is null and threads.post_id <> posts.id and posts.maintext = 0)
        ');
        //重新统计每一章节下准确的回帖数目
        DB::statement('
            update chapters
            set responded =
            (select count(id) from posts
            where chapters.id = posts.chapter_id and posts.deleted_at is null and posts.maintext =0 )
        ');

        //重新统计每一本书的字数
        DB::statement('
            update books
            set total_char=
            (select sum(distinct chapters.characters)
            from chapters, posts
            where books.id = chapters.book_id and chapters.post_id = posts.id and posts.deleted_at is null and posts.maintext = 1
            )
        ');

        //隐藏没有章节的文章
        $threads = DB::table("threads")
        ->join('books','books.id','=','threads.book_id')
        ->where('books.total_char','=',0)
	    ->update(['threads.public' => false]);

        //统计总积分：咸鱼+剩饭+点击+回复+下载+收藏
        DB::table('threads')
        ->update(['jifen'=>DB::raw('xianyu * 2000 + shengfan *100 + viewed*2 + responded * 100 + downloaded * 2000 + collection * 2000')]);
        //对非边缘文进行额外奖励，因为边缘文积分太高了
        DB::table('threads')
        ->where('bianyuan','=',0)
        ->update(['jifen'=>DB::raw('jifen*3 +2000')]);
        //计算均摊字数之后的一个分值
        DB::table('threads')
        ->join('books','threads.book_id','=','books.id')
        ->update(['threads.jifen'=>DB::raw('threads.jifen + books.total_char'), 'books.weighted_jifen'=>DB::raw('(threads.jifen * 1000) DIV (books.total_char + 10000)')]);
        //把同人信息都转移到books表格里。这样的话，tongrens表就可以删了
        DB::table('threads')
        ->join('books','threads.book_id','=','books.id')
        ->join('tongrens','tongrens.book_id','=','books.id')
        ->where('threads.channel_id','=',2)
        ->where('tongrens.tongren_yuanzhu_tag_id','=',0)
        ->update([
            'books.tongren_yuanzhu'=>DB::raw('tongrens.tongren_yuanzhu'),
            'books.tongren_cp'=>DB::raw('tongrens.tongren_cp'),
        ]);
        DB::table('users')
        ->where('no_logging', '<', Carbon::now()->toDateTimeString())
        ->update(['no_logging_or_not'=>0]);


    }
}
