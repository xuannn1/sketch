<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id')->withDefault();
    }
}
