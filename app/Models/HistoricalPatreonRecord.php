<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalPatreonRecord extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $dates = ['patreon_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
