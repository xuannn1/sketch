<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'user_id';
    public $timestamps = false;
}
