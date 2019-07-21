<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    use Traits\VoteTrait;
    use Traits\RewardTrait;
    use Traits\TypeValueChangeTrait;

    const UPDATED_AT = null;

    protected $dates = ['deleted_at','created_at'];
    protected $guarded = [];
    protected $count_types = ['upvote_count','forward_count','reply_count'];

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
    public function scopeIsPublic($query)
    {
        return $query->where('is_public',1);
    }

    public function scopeWithUser($query, $id)
    {
        return $query->where('user_id',$id);
    }

    public function scopeHasFollower($query, $id)
    {
        $query = $query->whereHas('followers', function ($query) use ($id){
            $query->where('followers.follower_id', '=', $id);
        });
        return $query;
    }

    public function latest_rewards()
    {
        return \App\Models\Reward::with('author')
        ->withType('status')
        ->withId($this->id)
        ->orderBy('created_at','desc')
        ->take(10)
        ->get();
    }

    public function latest_upvotes()
    {
        return \App\Models\Vote::with('author')
        ->withType('status')
        ->withId($this->id)
        ->withAttitude('upvote')
        ->orderBy('created_at','desc')
        ->take(10)
        ->get();
    }
}
