<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Thread;
use App\Label;
use Auth;

class Channel extends Model
{
    public function threads()
    {
      return $this->hasMany(Thread::class);
    }

    public function labels()
    {
      return $this->hasMany(Label::class, 'channel_id');
   }
   public function recent_threads()
   {
      if (Auth::check()){
         return Thread::where([['channel_id','=',$this->id],['public','=',1]])->orderBy('lastresponded_at', 'desc')->with('creator')->take(2)->get();
      }else{
         return Thread::where([['bianyuan','=',0],['channel_id','=',$this->id],['public','=',1]])->orderBy('lastresponded_at', 'desc')->with('creator')->take(2)->get();
      }
   }
}
