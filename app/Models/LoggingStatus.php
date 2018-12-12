<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoggingStatus extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'ip';

    public $timestamps = false;
    public $incrementing = false;
}
