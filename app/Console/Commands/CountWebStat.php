<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;
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
        $data['qiandaos']=DB::table('users')->where('qiandao_at','>',Carbon::now()->subday(1)->toDateTimeString())->count();
        $data['posts']=DB::table('posts')->where('created_at','>',Carbon::now()->subday(1)->toDateTimeString())->count();
        $data['chapters']=DB::table('posts')->where('created_at','>',Carbon::now()->subday(1)->toDateTimeString())->where('type','=','chapter')->count();
        $data['reviews']=DB::table('posts')->where('created_at','>',Carbon::now()->subday(1)->toDateTimeString())->where('type','=','review')->count();
        $data['new_users']=DB::table('users')->where('created_at','>',Carbon::now()->subday(1)->toDateTimeString())->count();
        $clicks_data_collection = DB::table('user_infos')->where('daily_clicks','>',0)->select(['user_id','daily_clicks'])->get();
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
        DB::table('user_infos')->update(['total_clicks'=>DB::raw('daily_clicks + total_clicks'), 'daily_clicks'=>0]);
    }
}
