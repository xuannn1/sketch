<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewardToken extends Model
{
    use SoftDeletes;
    const UPDATED_AT = null;
    protected $guarded = [];
    protected $dates = ['created_at','deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function redeemed_users()
    {
        return $this->hasMany(UserInfo::class, 'patreon_token_id');
    }
}
