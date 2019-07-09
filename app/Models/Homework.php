<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Homework extends Model
{

    protected $guarded = [];
    public $timestamps = false;

    public function registration_thread()
    {
        return $this->belongsTo(Thread::class, 'registration_thread_id');
    }

    public function profile_thread()
    {
        return $this->belongsTo(Thread::class, 'profile_thread_id');
    }

    public function students()
    {
        return $this->belongsToMany(Homework::class, 'homework_registrations', 'user_id', 'homework_id');
    }
}
