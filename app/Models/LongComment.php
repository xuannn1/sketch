<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LongComment extends Model
{
    protected $guarded = [];

    public function post(){
        return $this->belongsTo(Post::class, 'post_id')->withDefault();
    }
}
