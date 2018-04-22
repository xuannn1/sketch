<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
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
        $threadqueryid = '-thread-query-channel'.$channel->id
        .'-'.(Auth::check()?'logged':'not_logged')
        .'-'.($request->label? 'l'.$request->label:'no_l')
        .'-'.($request->sexual_orientation? 'so'.$request->sexual_orientation:'no_so')
        .'-'.($request->page? 'page'.$request->page:'page1');
        $threads = Cache::remember($threadqueryid, 2, function () use($request, $channel) {
            if($channel->id==1){
                $query = $this->join_no_tongren_thread_tables();
            }elseif($channel->id==2){
                $query = $this->join_thread_tables();
            }else{
                $query = $this->join_no_book_thread_tables();
            }
            $query->where([['threads.channel_id','=',$channel->id],['threads.deleted_at', '=', null],['threads.public','=',1]]);
            if($request->label){$query = $query->where('threads.label_id','=',$request->label);}
            if($request->label){$query = $query->where('threads.label_id','=',$request->label);}
            if($channel->id<=2){
                if($request->sexual_orientation){$query = $query->where('books.sexual_orientation','=',$request->sexual_orientation);}
            }
            if(!Auth::check()){$query = $query->where('threads.bianyuan','=',0);}
            if($channel->id==1){
                $query = $this->return_no_tongren_thread_fields($query);
            }elseif($channel->id==2){
                $query = $this->return_thread_fields($query);
            }else{
                $query = $this->return_no_book_thread_fields($query);
            }
            $threads = $query->orderby('threads.lastresponded_at', 'desc')
            ->paginate(config('constants.index_per_page'));
            return $threads;
        });

        $labels = Cache::remember('-channel-'.$channel->id.'-labels', 10, function () use($channel) {
            $labels = Label::inChannel($channel->id)
            ->withCount('threads')->orderBy('created_at','asc')
            ->get();
            return $labels;
        });

        if ($channel->channel_state==1){
            $sexual_orientation_count = Cache::remember('channel-'.$channel->id.'-sexual_orientation_count', 10, function () use($channel) {
                $sexual_orientation_count = DB::table('threads')
                    ->join('books', 'threads.book_id', '=', 'books.id')
                    ->where([['threads.deleted_at', '=', null],['threads.public','=',1], ['threads.channel_id','=',$channel->id]])
                    ->select('books.sexual_orientation', DB::raw('count(*) as total'))
                    ->groupBy('sexual_orientation')
                    ->get();
                return $sexual_orientation_count;
            });
            $s_count=[];
            foreach($sexual_orientation_count as $dataset){
                $s_count[$dataset->sexual_orientation]=$dataset->total;
            }
            $sexual_orientation_info = config('constants.book_info.sexual_orientation_info');
            return view('threads.index_channel', compact('threads', 'labels','channel','sexual_orientation_info','s_count'))->with('show_as_collections', false)->with('show_channel', false);
        }else{
            return view('threads.index_channel', compact('threads', 'labels','channel'))->with('show_as_collections', false)->with('show_channel', false);
        }
    }
}
