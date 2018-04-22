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
    public function index()
    {
        $group = Auth::check() ? Auth::user()->group : 10;
        $posts = Cache::remember('-longcomment-group'.$group.'-', 10, function () use($group) {
            $posts = DB::table('posts')
            ->join('users','users.id','=','posts.user_id')
            ->join('threads','threads.id','=','posts.thread_id')
            ->join('channels', 'threads.channel_id','=','channels.id')
            ->join('long_comments','posts.id','=','long_comments.post_id')
            ->where([['posts.deleted_at','=',null],['channels.channel_state','<',$group]])
            ->select('posts.*','threads.title as thread_title', 'users.name')
            ->orderBy('posts.created_at', 'desc')
            ->simplePaginate(config('constants.index_per_page'));
            return $posts;
        });
        return view('long_comments.index', compact('posts'));
    }
}
