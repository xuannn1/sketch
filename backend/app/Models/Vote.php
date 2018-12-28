<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = [];

    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
    * Get all of the owning votable models.
    */
    public function votable()
    {
        return $this->morphTo();
    }
}
