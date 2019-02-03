<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    protected $guarded = [];
    const UPDATED_AT = null;

    protected $dates = ['deleted_at'];
}