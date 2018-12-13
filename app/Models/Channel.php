<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Channel extends Model
{
    protected $guarded = [];

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
    public function labels()
    {
        return $this->hasMany(Label::class, 'channel_id');
    }
}
