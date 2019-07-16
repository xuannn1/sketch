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

    public function scopeWithType($query,$type='')
    {
        return $query->where('item_type',$type);
    }
    public function scopeWithUser($query,$id=0)
    {
        return $query->where('user_id',$id);
    }

}
