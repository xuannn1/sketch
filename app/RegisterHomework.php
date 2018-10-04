<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Thread;
use App\Post;
class RegisterHomework extends Model
{
   protected $guarded = [];
   public function thread()
   {
      return $this->belongsTo(Thread::class, 'thread_id')->select(['id','anonymous','majia','title'])->withDefault();
   }
   public function student()
   {
      return $this->belongsTo(User::class, 'user_id')->select(['id','name'])->withDefault();
   }
}
