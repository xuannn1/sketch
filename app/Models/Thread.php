<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Thread extends Model
{
   use Traits\ThreadFilterable;
   use Traits\RegularTraits;
   use SoftDeletes;
   use Searchable;
   protected $dates = ['deleted_at'];

   protected $guarded = [];

   public function path()
  {
     return '/threads/' . $this->id;
  }
  public function posts()
   {
      return $this->hasMany(Post::class, 'thread_id')->where('id','<>',$this->post_id);
   }

   public function mainpost()
   {
      return $this->belongsTo(Post::class, 'post_id')->withDefault();
   }

   public function lastpost()
   {
      return $this->hasOne(Post::class, 'thread_id','id')->latest();
   }

   public function creator()
   {
      return $this->belongsTo(User::class, 'user_id')->select(['id','name'])->withDefault();
   }

   public function user()
   {
      return $this->belongsTo(User::class, 'user_id')->withDefault();
   }

   public function addPost($post)
   {
      $this->posts()->create($post);
   }

   public function channel()
   {
      return $this->belongsTo(Channel::class, 'channel_id');
   }

   public function responded()
   {
       $this->update(['lastresponded_at' => Carbon::now()]);
       $this->increment('responded');
   }

   public function label()
   {
      return $this->belongsTo(Label::class, 'label_id');
   }

   public function tags()
   {
      return $this->belongsToMany(Tag::class, 'tagging_threads', 'thread_id', 'tag_id');
   }

   public function addTags($tags){
      foreach($tags as $tag){
         $book = TaggingThread::create([
            'thread_id' => $this->id,
            'tag_id' => $tag,
         ]);
      }
   }
   public function deleteTags(){
      $find = $this->hasMany(TaggingThread::class, 'thread_id')->delete();
   }

   public function book()
   {
      return $this->belongsTo(Book::class, 'book_id')->withDefault();
   }
   public function xianyu_voted(User $user, $ip)
   {
      $xianyus = $this->recentXianyus();
      $id = $user->id;
      if (($xianyus->where('user_id', $id)->first())||($xianyus->where('user_ip', $ip)->first())) {
         return true;
      }
      return false;
   }

   public function xianyus(){
      $xianyus = Xianyu::where('thread_id', $this->id);
      return ($xianyus);
   }

   public function recentXianyus(){
      $timelimit = Carbon::now()->subDays(7);//目前设置，一周内只能投一次咸鱼。
      $recent_xianyus = $this->xianyus()->where('created_at', '>', '$timelimit');
      return ($recent_xianyus);
   }

   public function collection(User $user)
   {
      return (Collection::where('thread_id', $this->id)->where('user_id', $user->id))->first();
   }

   public function homework()
   {
      return $this->belongsTo(Homework::class, 'homework_id')->withDefault();
   }
   public function searchableAs()
    {
        return 'threads_index';
    }
   public function toSearchableArray()
   {
       $array = $this->only('title','brief','body');
       return $array;
   }
   public function scopeNotSelf($query){

   }
   public function scopePublic($query){

   }

   public function registerhomework(){
       DB::table('register_homeworks')
       ->join('homeworks','homeworks.id','=','register_homeworks.homework_id')
       ->where('register_homeworks.user_id','=',Auth::id())
       ->where('homeworks.active','=',true)
       ->update(['register_homeworks.thread_id' => $thread->id]);
   }
}
