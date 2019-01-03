<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $guarded = [];
    const UPDATED_AT = null;

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id')->select(['id', 'user_id', 'channel_id',  'title',  'is_anonymous', 'majia', 'is_public', 'is_bianyuan', 'last_responded_at', 'last_added_chapter_at', 'deleted_at']);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_recommendation', 'recommendation_id', 'user_id')->select(['id', 'name']);
    }
}
