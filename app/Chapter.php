<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Post;
use App\Volumn;
use App\Book;

class Chapter extends Model
{
   use SoftDeletes;
   protected $dates = ['deleted_at'];
   protected $guarded = [];

   public function book()
   {
      return $this->belongsTo(Book::class, 'book_id')->withDefault();
   }
   public function posts()
   {
      return Post::where('chapter_id','=',$this->id)->get();
   }
   public function mainpost()
   {
      return $this->belongsTo(Post::class, 'post_id')->withDefault();
   }
   public function volumn()
   {
      return $this->belongsTo(Volumn::class, 'volumn_id')->withDefault();
   }
}
