<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use Traits\VoteTrait;
    use SoftDeletes;
    protected $dates = ['deleted_at','created_at'];
    protected $guarded = [];
    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id','level');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function scopeOrdered($query, $ordered='')
    {
        switch ($ordered) {
            case 'earliest_created'://最老
            return $query->orderBy('created_at', 'desc');
            break;
            default://默认按时间顺序排列，返回最新
            return $query->orderBy('created_at', 'desc');
        }
    }

    public function scopeHasFollower($query, $id)
    {
        $query = $query->whereHas('followers', function ($query) use ($id){
            $query->where('followers.follower_id', '=', $id);
        });
        return $query;
    }
}
