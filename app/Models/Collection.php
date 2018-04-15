<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
   protected $guarded = [];
   public $timestamps = false;
   public function user()
   {
      return $this->belongsTo(App\Models\User::class, 'user_id')->withDefault();
   }
}
