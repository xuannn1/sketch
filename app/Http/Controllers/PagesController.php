<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
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
    public function __construct()
    {
       $this->middleware('auth', [
         'only' => ['search'],
       ]);
    }

    public function home()
    {
      $group = 10;
      if (Auth::check()){
         $group = Auth::user()->group;
      }
      $channels = Channel::where('channel_state','<',$group)
      ->orderBy('orderby','asc')
      ->get();
      $quote = Quote::where('approved', true)->where('notsad', false)->inRandomOrder()->first();
      return view('pages/home',compact('channels', 'quote'));
    }

    public function about()
    {
      return view('pages/about');
    }

    public function help()
    {
      $data = config('constants');
      $webstat = WebStat::where('id','>',1)->orderBy('created_at', 'desc')->first();
      return view('pages/help',compact('data','webstat'));
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
        $join->whereIn('administrations.operation',[1,2,3,4,5,6,9]);
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
     ->simplePaginate(config('constants.index_per_page'));
     $admin_operation = config('constants.administrations');
     return view('pages.adminrecords',compact('records','admin_operation'));
    }

    public function search(Request $request){
        $user = Auth::user();
        if($user->lastsearched_at>Carbon::now()->subMinutes(15)->toDateTimeString()){
            return redirect()->back()->with('warning','15分钟内只能进行一次搜索');
        }else{
            $user->lastsearched_at=Carbon::now();
            $user->save();
        }
        if($request->search){
            if( $request->search_options=='threads'){
                $group = 10;
                if(Auth::check()){$group = Auth::user()->group;}
                $query = DB::table('threads')
                    ->join('users', 'threads.user_id', '=', 'users.id')
                    ->join('labels', 'threads.label_id', '=', 'labels.id')
                    ->join('channels', 'threads.channel_id','=','channels.id')
                    ->leftjoin('posts','threads.last_post_id','=', 'posts.id')
                    ->where([['threads.deleted_at', '=', null],['channels.channel_state','<',$group],['threads.public','=',1]])
                    ->where('threads.title', 'like', '%'.$request->search.'%');

                  $threads = $query->select('threads.*',        'channels.channelname','users.name','labels.labelname','posts.body as last_post_body')
                    ->orderby('threads.lastresponded_at', 'desc')
                    ->simplePaginate(config('constants.index_per_page'));
                  $show = ['channel' => false,'label' => false,];
                return    view('pages.search_threads',compact('threads','show'))->with('show_as_collections',0);
            }
            if($request->search_options=='users'){
                $users = User::where('name','like', '%'.$request->search.'%')->simplePaginate(config('constants.index_per_page'));
                return view('pages.search_users',compact('users'));
            }
            if($request->search_options=='tongren_yuanzhu'){
                $query = DB::table('threads')
                    ->join('books', 'threads.book_id', '=', 'books.id')
                    ->join('users', 'threads.user_id', '=', 'users.id')
                    ->join('labels', 'threads.label_id', '=', 'labels.id')
                    ->join('tongrens','books.id','=','tongrens.book_id')
                    ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
                    ->where([['threads.deleted_at', '=', null],['threads.public','=',1],['threads.channel_id','=',2]]);
                $query->where('tongrens.tongren_yuanzhu','like','%'.$request->search.'%');
            if ($request->tongren_cp){
                $query->where('tongrens.tongren_cp','like','%'.$request->tongren_cp.'%');
            }
            $books = $query->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title', 'chapters.responded as last_chapter_responded', 'chapters.post_id as last_chapter_post_id')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->simplePaginate(config('constants.index_per_page'));
            return view('pages.search_books', compact('books'))->with('show_as_collections', false);
          }
      }
  }
}
