<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Post;

class LongComment extends Model
{
   protected $guarded = [];

   public function post(){
      return $this->belongsTo(Post::class, 'post_id')->withDefault();
   }
}
