<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Label;
use App\Tag;
use App\Thread;
use App\Chapter;

class Book extends Model
{
   protected $guarded = [];
   protected $dates = ['deleted_at'];
   protected $casts =[
      'original' => 'boolean',
   ];

   public function thread()
   {
      return $this->belongsTo(Thread::class, 'thread_id')->withDefault();
   }
   public function book_status()
   {
      $array = [
         '1' => '连载',
         '2' => '完结',
         '3' => '暂停',
      ];
      return ($array[$this->book_status]);
   }
   public function book_length()
   {
      $array = [
         '1' => '短篇',
         '2' => '中篇',
         '3' => '长篇',
      ];
      return ($array[$this->book_length]);
   }
   public function originality()
   {
      if($this->original){
         return("原创");
      }else {
         return("同人");
      }
   }

   public function tongren()
   {
      return $this->hasOne(Tongren::class, 'book_id');
   }
   public function deleteTongren()
   {
      $this->hasMany(Tongren::class, 'book_id')->delete();
   }

   public function chapters()
   {
      return $this->hasMany(Chapter::class);
   }

   public function max_chapter_order()
   {
      return Chapter::where('book_id','=',$this->id)->max('chapter_order');
   }
   public function recent_volumn()
   {
      return Volumn::where('book_id','=',$this->id)->orderBy('volumn_order', 'desc')->first();
   }
   public function last_post()
   {
      return $this->belongsTo(Post::class, 'last_chapter_post_id')->withDefault();
   }
}
