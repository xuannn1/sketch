<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Follower extends Model
{
    protected $guarded = [];
    protected $table = 'Followers';
    public $timestamps = false;
}
