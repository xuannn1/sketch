<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use App\Channel;
use Auth;
use App\Quote;
use App\Label;


class ChannelsController extends Controller
{

    public function show(Channel $channel)
    {
      $labels = $channel->labels;
      $threads =DB::table('threads')
      ->join('users', 'threads.user_id', '=', 'users.id')
      ->join('labels', 'threads.label_id', '=', 'labels.id')
      ->join('channels', 'threads.channel_id','=','channels.id')
      ->leftjoin('posts','threads.last_post_id','=', 'posts.id')
      ->where([['channels.id','=',$channel->id],['threads.deleted_at', '=', null],['threads.public','=',1]])
      ->select('threads.*','users.name','labels.labelname','posts.body as last_post_body')
      ->orderby('threads.lastresponded_at', 'desc')
      ->paginate(Config::get('constants.index_per_page'));
      $show = [
        'channel' => $channel->channelname,
        'label' => false,
      ];
      $labelstats =DB::table('threads')
      ->join('users', 'threads.user_id', '=', 'users.id')
      ->join('labels', 'threads.label_id', '=', 'labels.id')
      ->where([['threads.channel_id','=',$channel->id],['threads.deleted_at', '=', null],['threads.public','=',1]])
      ->select('labels.id', DB::raw('count(threads.id) as total'))
      ->groupBy('labels.id')
      ->get();

      $total = 0;
      $labelsinfo=array();
      foreach($labels as $lab){
         $stat_total = 0;
         foreach($labelstats as $stat){
            if($lab->id==$stat->id){
               $stat_total = $stat->total;
               $total+=$stat->total;
            }
         }
         array_push($labelsinfo, array($lab->id,$stat_total,$lab->labelname));
      }
      $collections = false;
      $label = new Label;
      return view('threads.index_channel', compact('threads', 'labelsinfo','channel','label','show','total','collections'));
    }
   public function index()
   {
      if (Auth::check()){
         $channel_state_limit = Auth::user()->group;
      }else{
         $channel_state_limit = 10;
      }
      $channels = DB::table('channels')
      ->where('channel_state','<=',$channel_state_limit)
      ->orderBy('id')
      ->get();
      $quote = Quote::where('approved', true)->where('notsad', false)->inRandomOrder()->first();
      return view('threads.channelindex',compact('channels', 'quote'));
   }

}
