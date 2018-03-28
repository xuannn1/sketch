<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
       return Thread::inChannel($this->id)
        ->filterBianyuan(Auth::check())
        ->isPublic()
        ->withOrder('recentresponded')
        ->take(2)
        ->get();
   }

}
