<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tongren extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}
