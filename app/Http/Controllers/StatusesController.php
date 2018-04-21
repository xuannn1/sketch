<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Status;
use App\Models\User;
use Auth;
use Carbon\Carbon;


class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|string|max:180'
        ]);
        $content = trim($request->content);
        $last_status = Status::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->first();
        if (count($last_status) && strcmp($last_status->content, $content) === 0){
            return redirect()->back()->with('warning','您已成功提交状态，请不要重复提交哦！');
        }else{
            if(Carbon::now()->subMinutes(5)->toDateTimeString() < $last_status->created_at->toDateTimeString() ){
                return redirect()->back()->with('warning','5分钟内只能提交一条状态');
            }else{
                DB::transaction(function() use($content){
                    Auth::user()->statuses()->create([
                        'content' => $content
                    ]);
                    DB::table('followers')//告诉所有粉丝, 自己发布了新动态
                    ->join('users','users.id','=','followers.follower_id')
                    ->where([['followers.user_id','=',Auth::id()],['followers.keep_updated','=',true]])
                    ->update(['followers.updated'=>1,'users.collection_statuses_updated'=>DB::raw('users.collection_statuses_updated + 1')]);
                });
            }
        }
        return back()->with('success','动态成功发布');
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
        ->simplePaginate(config('constants.index_per_page'));
        $collections = false;
        return view('statuses.index', compact('statuses','collections'))->with('show_as_collections', false)->with('active',0);
    }
    public function collections()
    {
        $user = Auth::user();
        $statuses = DB::table('followers')
        ->join('users','followers.user_id','=','users.id')
        ->join('statuses','users.id','=','statuses.user_id')
        ->where([['followers.follower_id','=',$user->id], ['users.deleted_at', '=', null]])
        ->select('statuses.*','users.name','followers.keep_updated as keep_updated','followers.updated as updated')
        ->orderBy('statuses.created_at','desc')
        ->simplePaginate(config('constants.index_per_page'));
        return view('statuses.collections', compact('statuses','user'))->with('show_as_collections',1)->with('active',1);
    }
}
