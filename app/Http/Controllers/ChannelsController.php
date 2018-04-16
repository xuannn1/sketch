<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\Label;
use Auth;


class ChannelsController extends Controller
{
    public function show(Request $request, Channel $channel)
    {
        $group = 10;
        if(Auth::check()){$group = Auth::user()->group;}
        $query = DB::table('threads')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->join('channels', 'threads.channel_id','=','channels.id')
            ->leftjoin('posts','threads.last_post_id','=', 'posts.id');

        if($request->label){$query = $query->where('threads.label_id','=',$request->label);}

        $threads = $query->where([['threads.deleted_at', '=', null],['channels.channel_state','<',$group],['threads.public','=',1],['threads.channel_id','=',$channel->id]])
            ->select('threads.*', 'channels.channelname','users.name','labels.labelname','posts.body as last_post_body')
            ->orderby('threads.lastresponded_at', 'desc')
            ->paginate(config('constants.index_per_page'));

        $labels = Label::inChannel($channel->id)->withCount('threads')->orderBy('created_at','asc')->get();
        return view('threads.index_channel', compact('threads', 'labels','channel'))->with('show_as_collections', false);
    }
}
