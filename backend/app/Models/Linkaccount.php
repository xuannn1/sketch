<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Linkaccount extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function linker()
    {
        return $this->belongsTo(User::class, 'account1')->select('name');
    }
    public function linkee()
    {
        return $this->belongsTo(User::class, 'account2')->select('name');
    }

}
