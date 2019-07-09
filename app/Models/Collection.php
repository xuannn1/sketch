<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function owner()//谁收藏的
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id');
    }

    public function thread()//收藏的对象
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}
