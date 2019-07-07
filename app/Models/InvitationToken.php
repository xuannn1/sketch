<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvitationToken extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
    public function inactive_once()
    {
        $this->invitation_times+=1;
        $this->invited+=1;
        $this->save();
        return $this;
    }
}
