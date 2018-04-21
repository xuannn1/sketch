<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id','name'])->withDefault();
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id')->withDefault();
    }
}
