<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Administration extends Model
{
   protected $guarded = [];
   protected $dates = ['deleted_at'];
}
