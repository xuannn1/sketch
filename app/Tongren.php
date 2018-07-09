<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Book;
use App\Tag;

class Tongren extends Model
{
   protected $guarded = [];
   public function book()

   {
      return $this->belongsTo(Book::class, 'book_id')->withDefault();
   }

   public function original()
   {
      if ($this->tongren_yuanzhu_tag_id != 0){
         return Tag::find($this->tongren_yuanzhu_tag_id)->tagname;
      }
   }

   public function cp()
   {
      if ($this->tongren_CP_tag_id != 0){
         return Tag::find($this->tongren_CP_tag_id)->tagname;
      }
   }
}
