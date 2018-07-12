<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Sosadfun\Traits\ThreadTraits;
use App\Sosadfun\Traits\BookTraits;
use Auth;
use App\Models\Channel;
use App\Models\User;
use App\Models\Quote;
use App\Models\Thread;
use App\Models\Post;
use App\Models\WebStat;
use Carbon\Carbon;

class PagesController extends Controller
{
    use ThreadTraits;
    use BookTraits;

    public function __construct()
    {
        $this->middleware('auth', [
            'only' => ['search'],
        ]);
    }

    public function findthreads($channel_id, $take)
    {
        $threads = Cache::remember('home_ch'.$channel_id, 2, function () use($channel_id, $take) {
            return DB::table('threads')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->where([['threads.deleted_at', '=', null],['threads.channel_id','=',$channel_id],['threads.public','=',1],['threads.bianyuan','=',0]])
            ->select('threads.*','users.name')
            ->orderby('threads.lastresponded_at', 'desc')
            ->take($take)
            ->get();
        });
        return $threads;
    }

    public function findrecommendation($take)
    {
        $threads = Cache::remember('recommendation', 5, function () use($take) {
            return DB::table('threads')
            ->join('recommend_books', 'threads.id', '=', 'recommend_books.thread_id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->where([['recommend_books.valid','=',1],['threads.deleted_at', '=', null],['threads.book_id','>',0],['threads.public','=',1]])
            ->select('threads.*','users.name')
            ->inRandomOrder()
            ->take($take)
            ->get();
        });
        return $threads;
    }

    public function home()
    {
        $threads = [];
        $group = Auth::check()? Auth::user()->group : 10;
        $channels = Channel::where('channel_state','<',$group)->orderBy('orderBy','asc')->get();
        foreach($channels as $channel)
        {
            switch ($channel->id) {
                case 1://原创，拿三个
                    $take =3;
                    break;
                case 2://同人
                case 3://作业
                case 4://读写
                case 5://日常
                case 6://随笔
                    $take = 2;
                    break;
                default://其他
                    $take = 1;
            }
            $threads[$channel->id] = $this->findthreads($channel->id,$take);
        }
        $quotes1 = Quote::where('approved', true)->where('notsad', true)->inRandomOrder()->take(2);
        $quotes = Quote::where('approved', true)->where('notsad', false)->inRandomOrder()->take(8)->union($quotes1)->inRandomOrder()->get();
        $recommends = $this->findrecommendation(6);
        return view('pages/home',compact('channels', 'quotes','threads','recommends'));
    }
    public function about()
    {
        return view('pages/about');
    }

    public function help()
    {
        $users_online = Cache::remember('-users-online-count', 5, function () {
            $users_online = DB::table('cache')
            ->where('key','like','sosad-usr-on-%')
            ->where('expiration','>', time()-60*30)
            ->count();
            return $users_online;
        });

        $data = config('constants');
        $webstat = WebStat::where('id','>',1)->orderBy('created_at', 'desc')->first();
        return view('pages/help',compact('data','webstat','users_online'));
    }

    public function test()
    {
        return view('pages/test');
    }

    public function error($error_code)
    {
        $errors = array(
            "401" => "抱歉，您未登陆",
            "403" => "抱歉，由于设置，您无权限访问该页面",
            "404" => "抱歉，该页面不存在或已删除",
            "405" => "抱歉，数据库不支持本操作",//修改或增添
            "409" => "抱歉，数据冲突。",
        );
        $error_message = $errors[$error_code];
        return view('errors.errorpage', compact('error_message'));
    }
    public function administrationrecords()
    {
        $records = DB::table('administrations')
        ->join('users','administrations.user_id','=','users.id')
        ->leftjoin('threads',function($join)
        {
            $join->whereIn('administrations.operation',[1,2,3,4,5,6,9,15,16]);
            $join->on('administrations.item_id','=','threads.id');
        })
        ->leftjoin('posts',function($join)
        {
            $join->whereIn('administrations.operation',[7,10,11,12]);
            $join->on('administrations.item_id','=','posts.id');
        })
        ->leftjoin('post_comments',function($join)
        {
            $join->where('administrations.operation','=',8);
            $join->on('administrations.item_id','=','post_comments.id');
        })
        ->leftjoin('users as operated_users',function($join)
        {
            $join->whereIn('administrations.operation',[13,14]);
            $join->on('administrations.item_id','=','operated_users.id');
        })
        ->where('administrations.deleted_at','=',null)
        ->select('users.name','administrations.*','threads.title as thread_title','posts.body as post_body','post_comments.body as postcomment_body','operated_users.name as operated_users_name' )
        ->orderBy('administrations.created_at','desc')
        ->paginate(config('constants.index_per_page'));
        $admin_operation = config('constants.administrations');
        return view('pages.adminrecords',compact('records','admin_operation'));
    }

    public function search(Request $request){
        $user = Auth::user();
        $cool_time = Auth::user()->user_level>=3 ? 1:5;
        if((!Auth::user()->admin)&&($user->lastsearched_at>Carbon::now()->subMinutes($cool_time)->toDateTimeString())){
            return redirect()->back()->with('warning',(string)$cool_time.'分钟内只能进行一次搜索');
        }else{
            $user->lastsearched_at=Carbon::now();
            $user->save();
        }
        $group = 10;
        if(Auth::check()){$group = Auth::user()->group;}
        if($request->search){
            if( $request->search_options=='threads'){
                $query = $this->join_thread_tables()
                ->where([['threads.deleted_at', '=', null],['channels.channel_state','<',$group],['threads.public','=',1],['threads.title','like','%'.$request->search.'%']]);
                $threads = $this->return_thread_fields($query)
                ->orderby('threads.lastresponded_at', 'desc')
                ->simplePaginate(config('constants.index_per_page'));
                $show = ['channel' => false,'label' => false,];
                return view('pages.search_threads',compact('threads','show'))->with('show_as_collections',0)->with('show_channel',1);
            }
            if($request->search_options=='users'){
                $users = User::where('name','like', '%'.$request->search.'%')->simplePaginate(config('constants.index_per_page'));
                return view('pages.search_users',compact('users'));
            }
            if($request->search_options=='tongren_yuanzhu'){
                $query = $this->join_book_tables()
                ->where([['threads.deleted_at', '=', null],['threads.public','=',1],['threads.channel_id','=',2]]);
                $query->where('tongrens.tongren_yuanzhu','like','%'.$request->search.'%');
                if ($request->tongren_cp){
                    $query->where('tongrens.tongren_cp','like','%'.$request->tongren_cp.'%');
                }
                $books = $this->return_book_fields($query)
                ->orderby('threads.lastresponded_at', 'desc')
                ->simplePaginate(config('constants.index_per_page'));

                return view('pages.search_books', compact('books'))->with('show_as_collections', false);
            }
        }
    }
}
