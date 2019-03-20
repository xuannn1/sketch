<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];
    const UPDATED_AT = null;

    public function user(){
    	return $this->belongsTo(User::class,'user_id');
    }
    public function notifiable(){
    	return $this->morphTo();
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id');
    }
}
