<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class VotePosts extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
    public function post()
    {
        return $this->belongsTo(User::class, 'post_id')->withDefault();
    }
}
