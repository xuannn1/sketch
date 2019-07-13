<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon;
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
        $clicks_data_collection = DB::table('users')->where('daily_clicks','>',0)->select(['id as user_id','daily_clicks','daily_posts','daily_chapters','daily_characters'])->get();
        $data['daily_clicks']=$clicks_data_collection->sum('daily_clicks');
        $data['daily_clicked_users']=$clicks_data_collection->count();
        $data['daily_clicks_average']=$clicks_data_collection->average('daily_clicks');
        $data['daily_clicks_median']=$clicks_data_collection->median('daily_clicks');
        $clicks_data_collection = $clicks_data_collection->map(function ($clicks_data) {
            $clicks_data->{'created_at'} = Carbon::now()->toDateString();
        return (array)$clicks_data;
        });
        $clicks_data_to_insert = $clicks_data_collection->toArray();
        foreach (array_chunk($clicks_data_to_insert, 1000) as $t){
            DB::table('historical_users_data')->insert($t);
        }
        WebStat::create($data);
        DB::table('users')->update(['clicks'=>DB::raw('daily_clicks + clicks'), 'daily_clicks'=>0]);
    }
}
