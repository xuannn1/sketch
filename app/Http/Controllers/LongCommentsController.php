<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Models\LongComment;
use App\Models\Post;
use Auth;

class LongCommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }
    public function index(Request $request)
    {
        $bianyuan = Auth::check()? 1:0;
        $posts = Cache::remember('-longcomment-BY'.$bianyuan.'-'.$request->page, 10, function () use( $request) {
            $query = DB::table('posts')
            ->join('users','users.id','=','posts.user_id')
            ->join('threads','threads.id','=','posts.thread_id')
            ->join('channels', 'threads.channel_id','=','channels.id')
            ->join('long_comments','posts.id','=','long_comments.post_id')
            ->where([['posts.deleted_at','=',null],['channels.channel_state','<=',10],['threads.public','=',1],['long_comments.approved','=',1],['posts.as_longcomment','=',1]]);
            if (!Auth::check()){
                $query->where('threads.bianyuan','=',0);
            }
            $posts = $query->select('posts.*','threads.title as thread_title', 'users.name','long_comments.reviewed','long_comments.approved')
            ->orderBy('posts.created_at', 'desc')
            ->simplePaginate(config('constants.index_per_page'));
            return $posts;
        });
        return view('long_comments.index', compact('posts'))->with('active',0)->with('as_longcomments',1);
    }
}
