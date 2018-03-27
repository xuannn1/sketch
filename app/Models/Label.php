<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Thread;
use App\Models\Channel;

class Label extends Model
{
    public function threads()
    {
       return $this->hasMany(Thread::class);
    }
    public function Channel()
   {
      return $this->belongsTo(Channel::class);
   }
   public function scopeInChannel($query,$channel)
   {
       if ($channel){
           return $query->where('channel_id', '=', $channel);
       }else{
           return $query;
       }
   }

}
