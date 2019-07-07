<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\PageObjects;
use App\Helpers\ConstantObjects;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use Carbon\Carbon;

class PagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'only' => ['search', 'self_adminnistrationrecords'],
        ]);
    }

    public function home()
    {
        $quotes = PageObjects::recent_quotes();
        $long_recom = PageObjects::recent_long_recommendations();
        $short_recom = PageObjects::recent_short_recommendations();
        dd($quotes);
        return view('pages/home',compact('quotes','recom_sr','recom_lg'));
    }
    public function about()
    {
        return view('pages/about');
    }

    public function help()
    {
        $users_online = Cache::remember('users-online-count', config('constants.online_count_interval'), function () {
            $users_online = DB::table('logging_statuses')
            ->where('logged_on', '>', time()-60*30)
            ->count();
            return $users_online;
        });
        $data = config('constants');
        $webstat = Cache::remember('webstat-yesterday', config('constants.online_count_interval'), function () {
            $webstat = WebStat::where('id','>',1)->orderBy('created_at', 'desc')->first();
            return $webstat;
        });
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
        $records = $this->findAdminRecords(0,config('constants.index_per_page'));
        $admin_operation = config('constants.administrations');
        return view('pages.adminrecords',compact('records','admin_operation'))->with('active','1');
    }

    public function self_adminnistrationrecords()
    {
        $records = $this->findAdminRecords(Auth::id(),config('constants.index_per_page'));
        $admin_operation = config('constants.administrations');
        return view('pages.adminrecords',compact('records','admin_operation'))->with('active','2');
    }

    public function search(Request $request){
        $user = Auth::user();
        $cool_time = 1;
        if((!Auth::user()->admin)&&($user->lastsearched_at>Carbon::now()->subMinutes($cool_time)->toDateTimeString())){
            return redirect('/')->with('warning','1分钟内只能进行一次搜索');
        }else{
            $user->lastsearched_at=Carbon::now();
            $user->save();
        }
        $group = 10;
        if(Auth::check()){$group = Auth::user()->group;}
        if(($request->search)&&($request->search_options=='threads')){
            $query = $this->join_no_book_thread_tables()
            ->where([['threads.deleted_at', '=', null],['channels.channel_state','<',$group],['threads.public','=',1],['threads.title','like','%'.$request->search.'%']]);
            $simplethreads = $this->return_no_book_thread_fields($query)
            ->orderBy('threads.lastresponded_at', 'desc')
            ->simplePaginate(config('constants.index_per_page'))
            ->appends($request->only('page','search','search_options'));
            $show = ['channel' => false,'label' => false,];
            return view('pages.search_threads',compact('simplethreads','show'))->with('show_as_collections',0)->with('show_channel',1);
        }
        if(($request->search)&&($request->search_options=='users')){
            $users = User::where('name','like', '%'.$request->search.'%')->simplePaginate(config('constants.index_per_page'))
            ->appends($request->only('page','search','search_options'));
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
            ->orderBy('threads.lastresponded_at', 'desc')
            ->simplePaginate(config('constants.index_per_page'))
            ->appends($request->only('page','search','tongren_cp','search_options'));
            return view('pages.search_books', compact('books'))->with('show_as_collections', false);
        }
        return redirect('/')->with('warning','请输入搜索内容');
    }

    public function contacts()
    {
        return view('pages.contacts');
    }

    public function recommend_records()
    {
        $recommend_books = DB::table('threads')
        ->join('users', 'threads.user_id', '=', 'users.id')
        ->join('recommend_books', 'threads.id', '=', 'recommend_books.thread_id')
        ->where('long', '=', 0)
        ->where('valid','=',1)
        ->select('threads.user_id', 'threads.bianyuan', 'threads.locked', 'threads.public', 'threads.noreply', 'threads.anonymous', 'threads.majia', 'threads.title', 'recommend_books.id', 'recommend_books.thread_id', 'recommend_books.recommendation', 'users.name', 'recommend_books.created_at')
        ->orderBy('recommend_books.id','desc')
        ->paginate(config('constants.items_per_page'));

        return view('pages.recommend_records', compact('recommend_books'))->with('active', 1);
    }
}
