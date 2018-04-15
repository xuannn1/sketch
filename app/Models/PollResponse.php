<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollResponse extends Model
{
    protected $guarded = [];
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }
}
