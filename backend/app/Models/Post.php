<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    const UPDATED_AT = null;

    protected $hidden = [
        'creation_ip',
    ];

    protected $columns = array('id', 'user_id', 'thread_id', 'body', 'is_anonymous', 'majia', 'creation_ip', 'created_at', 'last_edited_at', 'reply_to_post_id', 'reply_to_post_preview', 'reply_position', 'is_component', 'is_post_comment', 'use_markdown', 'use_indentation', 'up_votes', 'down_votes', 'fold_votes', 'funny_votes', 'xianyus', 'shengfans', 'replies', 'is_folded', 'is_longpost', 'allow_as_longpost', 'is_bianyuan', 'last_responded_at', 'deleted_at')  ; // 从这里排除可以不检出的column

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Post::class, 'reply_to_post_id');
    }

    public function parent()
    {
        return $this->belongsTo(Post::class, 'reply_to_post_id');
    }

    public function votes()
    {
        return $this->morphMany('App\Models\Vote', 'votable');
    }

    public function chapter()
    {
        return $this->hasOne(Chapter::class, 'post_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name');
    }

    public function mainchapter()
    {
        return $this->hasOne(Chapter::class, 'post_id');
    }

    public function scopeExclude($query, $value = array())
    {
        return $query->select( array_diff( $this->columns,(array) $value));
    }
    public function scopeUserOnly($query, $userOnly)
    {
        if($userOnly){
            return $query->where('user_id', $userOnly)->where('is_anonymous', false);
        }
        return $query;
    }
}
