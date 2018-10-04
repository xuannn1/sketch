<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Thread;
use App\Channel;

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

}
