<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\Label;
use Auth;


class ChannelsController extends Controller
{
    public function show(Request $request, Channel $channel)
    {
        $threads = Thread::inChannel($channel->id)->inLabel(request('label'))->withOrder('recentresponded')->with('creator','label','channel','lastpost')->simplePaginate(config('constants.index_per_page'));
        $labels = Label::inChannel($channel->id)->withCount('threads')->get();
        return view('threads.index_channel', compact('threads', 'labels','channel'))->with('show_as_collections', false);
    }
}
