<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Xianyu extends Model
{
    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id','name'])->withDefault();
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id')->select(['id','user_id']);
    }
}
