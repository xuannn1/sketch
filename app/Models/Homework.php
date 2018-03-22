<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Thread;
use App\RegisterHomework;

class Homework extends Model
{

   protected $guarded = [];
   public $timestamps = false;
   public function registered_students()
   {
      $homework_id = $this->id;
      $registered = DB::table('register_homeworks')
      ->join('users','users.id','=','register_homeworks.user_id')
      ->where('register_homeworks.homework_id','=',$homework_id)
      ->select('users.id','users.name','register_homeworks.majia')
      ->orderby('register_homeworks.created_at', 'asc')
      ->get();
      return $registered;
   }
   public function registered()
   {
      return $this->belongsToMany(User::class, 'register_homeworks', 'homework_id', 'user_id');
   }
   public function registerhomeworks()
   {
      return $this->hasMany(RegisterHomework::class, 'homework_id')->orderby('thread_id', 'asc');
   }
   public function thread()
   {
      return $this->hasOne(Thread::class, 'homework_id');
   }
}
