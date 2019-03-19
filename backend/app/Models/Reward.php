<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    //
    protected $guarded = [];
    const UPDATED_AT = null;
    public function rewardable(){
    	return $this->morphTo();
    }

    public function user(){
    	return $this->belongsTo('App\Models\User','user_id');
    }
}
