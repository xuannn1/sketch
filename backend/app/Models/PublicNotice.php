<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PublicNotice extends Model
{
    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function poster()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'title_id');
    }
}
