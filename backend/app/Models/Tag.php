<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];
    const UPDATED_AT = null;

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
        return $this->belongsTo(Tag::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Tag::class, 'parent_id');
    }
    public function admin_only()//判断这个tag是否可以被用户自己控制
    {
        return in_array($this->tag_type, config('tag.limits.admin_only'));
    }
    public function channel()
    {
        return collect(config('channel'))->keyby('id')->get($this->channel_id);
    }
}