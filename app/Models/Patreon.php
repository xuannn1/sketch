<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patreon extends Model
{
    const UPDATED_AT = null;
    protected $guarded = [];
    protected $dates = ['created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function last_patreon()
    {
        return $this->belongsTo(HistoricalPatreonRecord::class, 'last_patreon_id');
    }
}
