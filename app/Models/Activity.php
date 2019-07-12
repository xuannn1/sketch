<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function item(){
    	return $this->morphTo();
    }
}
