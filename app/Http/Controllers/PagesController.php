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
        return DB::table('threads')
        ->join('users', 'threads.user_id', '=', 'users.id')
        ->where([['threads.deleted_at', '=', null],['threads.channel_id','=',$channel_id],['threads.public','=',1],['threads.bianyuan','=',0]])
        ->select('threads.*','users.name')
        ->orderby('threads.lastresponded_at', 'desc')
        ->take($take)
        ->get();
    }

    public function findrecommendedbooks_short($take)//寻找合适的推荐，非长评:需要valid，新的（非past）$take-3个，past 3个
    {
        $recommendation1 = DB::table('recommend_books')
        ->where([['valid','=',1],['past','=',1],['long','=',0]])
        ->select('*')
        ->inRandomOrder()
        ->take(3);
        return DB::table('recommend_books')
        ->where([['valid','=',1],['past','=',0],['long','=',0]])
        ->select('*')
        ->inRandomOrder()
        ->take($take-3)
        ->union($recommendation1)
        ->get();
    }

    public function findrecommendedbooks_long($take)//寻找合适的长评推荐
    {
        return DB::table('recommend_books')
        ->where([['valid','=',1],['past','=',0],['long','=',1]])
        ->select('*')
        ->inRandomOrder()
        ->take($take)
        ->get();
    }

    public function findquotes()
    {
        $quotes1 = DB::table('quotes')
        ->join('users', 'quotes.user_id', '=', 'users.id')
        ->where([['quotes.approved', '=', 1], ['quotes.notsad','=',0]])
        ->inRandomOrder()
        ->select('quotes.*','users.name')
        ->take(18);
        return DB::table('quotes')
        ->join('users', 'quotes.user_id', '=', 'users.id')
        ->where([['quotes.approved', '=', 1], ['quotes.notsad','=',1]])
        ->inRandomOrder()
        ->select('quotes.*','users.name')
        ->take(2)
        ->union($quotes1)
        ->inRandomOrder()
        ->get();
    }

    public function home()
    {
        $threads = [];
        $group = Auth::check()? Auth::user()->group : 10;
        $home_info = Cache::remember('home_g'.$group, 5, function () use ($group) {
            $home_info = [];
            $channels = Channel::where('channel_state','<',$group)->orderBy('orderBy','asc')->select('id','channelname','channel_state','orderBy')->get();
            $home_info['channels']=$channels;
            $home_info['quotes']=$this->findquotes();
            $home_info['recom_sr'] = $this->findrecommendedbooks_short(6);
            $home_info['recom_lg'] = $this->findrecommendedbooks_long(1);
            $threads = [];
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
            $home_info['threads'] = $threads;
            return $home_info;
        });
        $channels=$home_info['channels'];
        $quotes = $home_info['quotes'];
        $recom_sr = $home_info['recom_sr'];
        $recom_lg = $home_info['recom_lg'];
        $threads = $home_info['threads'];
        return view('pages/home',compact('channels','quotes','recom_sr','recom_lg','threads'));
    }
    public function about()
    {
        return view('pages/about');
    }

    public function help()
    {
        $users_online = Cache::remember('-users-online-count', 20, function () {
            $users_online = DB::table('logging_statuses')
            ->where('logged_on','>', time()-60*30)
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
        if(($request->search)&&($request->search_options=='threads')){
            $query = $this->join_thread_tables()
            ->where([['threads.deleted_at', '=', null],['channels.channel_state','<',$group],['threads.public','=',1],['threads.title','like','%'.$request->search.'%']]);
            $threads = $this->return_thread_fields($query)
            ->orderby('threads.lastresponded_at', 'desc')
            ->simplePaginate(config('constants.index_per_page'));
            $show = ['channel' => false,'label' => false,];
            return view('pages.search_threads',compact('threads','show'))->with('show_as_collections',0)->with('show_channel',1);
        }
        if(($request->search)&&($request->search_options=='users')){
            $users = User::where('name','like', '%'.$request->search.'%')->simplePaginate(config('constants.index_per_page'));
            return view('pages.search_users',compact('users'));
        }
        if($request->search_options=='tongren_yuanzhu'){
            $query = $this->join_book_tables()
                ->where([['threads.deleted_at', '=', null],['threads.public','=',1],['threads.channel_id','=',2]]);
            if ($request->search){
                $query->where('tongrens.tongren_yuanzhu','like','%'.$request->search.'%');
            }
            if ($request->tongren_cp){
                $query->where('tongrens.tongren_cp','like','%'.$request->tongren_cp.'%');
            }
            $books = $this->return_book_fields($query)
            ->orderby('threads.lastresponded_at', 'desc')
            ->simplePaginate(config('constants.index_per_page'));
            return view('pages.search_books', compact('books'))->with('show_as_collections', false);
        }
        return redirect()->back()->with('warning','请输入搜索内容');
    }
}
