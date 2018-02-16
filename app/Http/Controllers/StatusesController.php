<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use App\Status;
use App\User;
use Auth;


class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'content' => 'required|max:180'
      ]);
      Auth::user()->statuses()->create([
        'content' => $request->content
      ]);
      DB::table('followers')//告诉所有粉丝, 自己发布了新动态
      ->join('users','users.id','=','followers.follower_id')
      ->where([['followers.user_id','=',Auth::id()],['followers.keep_updated','=',true]])
      ->update(['followers.updated'=>1,'users.collection_statuses_updated'=>DB::raw('users.collection_statuses_updated + 1')]);
      return redirect()->back()->with('success','动态成功发布');
    }

    public function destroy(Status $status)
    {
      if($status->user_id == Auth::id()){
         $status->delete();
         return redirect()->route('user.show',Auth::id())->with('success', '动态已被成功删除！');
      }else{
         return redirect()->route('error', ['error_code' => '403']);
      }
    }
    public function index()
    {
      $statuses = DB::table('statuses')
         ->join('users','statuses.user_id','=','users.id')
         ->where('users.deleted_at', '=', null)
         ->select('statuses.*','users.name')
         ->orderBy('statuses.created_at','desc')
         ->paginate(Config::get('constants.index_per_page'));
      $collections = false;
      return view('statuses.index', compact('statuses','collections'));
    }
}
