<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
   protected $guarded = [];
   protected $dates = ['deleted_at'];

   
}
