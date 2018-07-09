<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Quote extends Model
{
   protected $guarded = [];

   public function creator()
   {
      return $this->belongsTo(User::class, 'user_id')->withDefault();
   }
}
