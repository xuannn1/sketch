<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $guarded = [];
    const UPDATED_AT = null;
    protected $dates = ['created_at'];

    public function rewardable(){
    	return $this->morphTo();
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User','user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id','level');
    }

    public function scopeWithType($query, $type='')
    {
    	return $query->where('rewardable_type', '=', $type);
    }

    public function scopeWithId($query, $id=0)
    {
    	return $query->where('rewardable_id', '=', $id);
    }
}
