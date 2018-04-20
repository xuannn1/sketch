<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sosadfun\Traits\ThreadTraits;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\Label;
use Auth;


class ChannelsController extends Controller
{
    use ThreadTraits;

    public function show(Request $request, Channel $channel)
    {
        $group = 10;
        if(Auth::check()){$group = Auth::user()->group;}
        $query = $this->join_thread_tables()
            ->where([['threads.deleted_at', '=', null],['channels.channel_state','<',$group],['threads.public','=',1], ['threads.channel_id','=',$channel->id]]);
        if($request->label){$query = $query->where('threads.label_id','=',$request->label);}
        $threads = $this->return_thread_fields($query)
            ->orderby('threads.lastresponded_at', 'desc')
            ->paginate(config('constants.index_per_page'));
        $labels = Label::inChannel($channel->id)
            ->withCount('threads')->orderBy('created_at','asc')
            ->get();
        return view('threads.index_channel', compact('threads', 'labels','channel'))->with('show_as_collections', false)->with('show_channel', false);
    }
}
