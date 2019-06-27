<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailModifyHistory extends Model
{
    const UPDATED_AT = null;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

}
