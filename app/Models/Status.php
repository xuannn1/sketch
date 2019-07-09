<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use Traits\VoteTrait;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id');
    }
}
