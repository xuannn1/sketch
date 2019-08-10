<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use Traits\VoteTrait;
    use Traits\RewardTrait;
    use Traits\TypeValueChangeTrait;

    protected $guarded = [];
    const UPDATED_AT = null;
    protected $dates = ['created_at'];
    protected $count_types = array('fish');

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id','level');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id')->select('id','name', 'title_id');
    }

    public function scopeNotSad($query)
    {
        return $query->where('notsad','=',true);
    }
    public function scopeWithReviewState($query, $state = '')
    {
        if($state==='notYetReviewed'){
            $query = $query->where('reviewed','=',0);
        }
        return $query;
    }

    public function latest_rewards()
    {
        return \App\Models\Reward::with('author')
        ->withType('quote')
        ->withId($this->id)
        ->orderBy('created_at','desc')
        ->take(10)
        ->get();
    }

}
