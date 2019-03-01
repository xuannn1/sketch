<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Sosadfun\Traits\ColumnTrait;

class Collection extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    use ColumnTrait;

    public function owner()//谁收藏的
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id');
    }

    public function collectee()//收藏的对象，简略呈现
    {
        return $this->belongsTo(Thread::class, 'thread_id')->select($this->threadbrief_columns);
    }

    public function post()//对应在thread里面的post内容
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function thread()//收藏的对象
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

}
