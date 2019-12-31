<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function owners()
    {
        return $this->belongsToMany(User::Class, 'title_user', 'title_id', 'user_id');
    }
}
