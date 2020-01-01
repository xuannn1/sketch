<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Sosadfun\Traits\ColumnTrait;

class PasswordReset extends Model
{
	public $timestamps = false;
    protected $table='password_resets';
    protected $guarded = [];
    protected $primaryKey = 'id';
    const UPDATED_AT = null;
}
