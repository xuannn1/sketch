<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalDonationRecord extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $dates = ['donated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id','level');
    }
}
