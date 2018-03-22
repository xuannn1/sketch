<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Channel;
use App\Models\User;
use App\Models\Quote;
use App\Models\Thread;
use App\Models\Post;

class PagesController extends Controller
{

    public function home()
    {
        $group = 10;
      if (Auth::check()){
         $group = Auth::user()->group;
      }
      $channels = Channel::where('channel_state','<',$group)
      ->orderBy('id','asc')
      ->get();

      $quote = Quote::where('approved', true)->where('notsad', false)->inRandomOrder()->first();
      return view('pages/home',compact('channels', 'quote'));
    }

    public function about()
    {
      $data = Config::get('constants');
      return view('pages/about',compact('data'));
    }

    public function help()
    {
      $data = Config::get('constants');
      return view('pages/help',compact('data'));
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
     ->paginate(Config::get('constants.index_per_page'));
     $admin_operation = Config::get('constants.administrations');
     return view('pages.adminrecords',compact('records','admin_operation'));
  }

  public function search(Request $request){
      $users = User::search(request('search'))->paginate(Config::get('constants.index_per_part'));
      $threads = Thread::search(request('search'))->paginate(Config::get('constants.index_per_part'));$threads->load('creator','channel','label');
      $posts = Post::search(request('search'))->paginate(Config::get('constants.index_per_part'));$posts->load('thread');
      $show = [
        'channel' => false,
        'label' => false,
      ];
      $collections = false;
      return view('pages.search',compact('users','threads','posts','show','collections'));
  }
}
