<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Sosadfun\Traits\ColumnTrait;

class Review extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'post_id';
    use ColumnTrait;

    public function mainpost()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function post_brief()
    {
        return $this->belongsTo(Post::class, 'post_id')->select($this->postbrief_columns);
    }

    public function reviewee()//被评论的文章
    {
        return $this->belongsTo(Thread::class, 'thread_id')->select($this->threadbrief_columns);
    }

}
