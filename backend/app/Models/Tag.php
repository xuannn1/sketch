<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function threads()
    {
        return $this->belongsToMany(Thread::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function parent()
    {
        return $this->hasOne(Tag::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Tag::class, 'parent_id');
    }
    public function user_not_manageable()//判断这个tag是否可以被用户自己控制
    {
        return config('tag.limits.user_not_manageable')[$this->tag_type]?? false;
    }
}
