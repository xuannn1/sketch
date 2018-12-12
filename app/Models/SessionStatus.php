<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionStatus extends Model
{
    protected $primaryKey = 'session_token';
    protected $guarded = [];
    public $timestamps = false;
}
