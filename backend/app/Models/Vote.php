<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    //
    //protected $primaryKey = ['user_id','votable_type','votable_id'];  
    //public $incrementing = false;
    //protected $keyType = 'string';
    protected $guarded = [];
    const UPDATED_AT = null;

    public function user(){
    	return $this->belongsTo(User::class,'user_id');
    }
    public function votable(){
    	return $this->morphTo();
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id');
    }
}

