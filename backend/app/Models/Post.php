<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    const UPDATED_AT = null;

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'item_id')->where('item_type', config('constants.vote_info.item_types.post'));
    }
    public function likevotes()
    {
        return $this->hasMany(Vote::class,'item_id')->where('item_type', config('constants.vote_info.item_types.post'))->where('attitude_type',1);
    }
}
