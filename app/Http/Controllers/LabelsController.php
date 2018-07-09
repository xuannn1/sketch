<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use App\Channel;
use App\Thread;
use App\Label;

class LabelsController extends Controller
{
   public function show(Label $label)
   {
      $channel = $label->channel;
      $threads =DB::table('threads')
      ->join('users', 'threads.user_id', '=', 'users.id')
      ->leftjoin('posts','threads.last_post_id','=', 'posts.id')
      ->where([['threads.label_id','=',$label->id],['threads.deleted_at', '=', null],['threads.public','=',1]])
      ->select('threads.*','users.name','posts.body as last_post_body')
      ->orderby('threads.lastresponded_at', 'desc')
      ->paginate(Config::get('constants.index_per_page'));

      $labelstats =DB::table('threads')
      ->join('users', 'threads.user_id', '=', 'users.id')
      ->join('labels', 'threads.label_id', '=', 'labels.id')
      ->where([['threads.channel_id','=',$channel->id],['threads.deleted_at', '=', null],['threads.public','=',1]])
      ->select('labels.id', DB::raw('count(threads.id) as total'))
      ->groupBy('labels.id')
      ->get();
      $labels = $channel->labels;

      $show = [
         'channel' => $channel->channelname,
         'label' => $label->labelname,
      ];
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
      return view('threads.index_channel', compact('threads', 'labelsinfo','channel','label','show','total','collections'));
   }
}
