<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'post_id';

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id')->withDefault();
    }

    public function reviewee()//被评论的文章
    {
        return $this->belongsTo(Thread::class, 'thread_id')->withDefault();
    }

}
