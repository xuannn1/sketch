<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tongren extends Model
{
   protected $guarded = [];
   public function book()

   {
      return $this->belongsTo(Book::class, 'book_id')->withDefault();
   }

   public function yuanzhu()
   {
      if ($this->tongren_yuanzhu_tag_id != 0){
         return Tag::find($this->tongren_yuanzhu_tag_id)->tag_explanation;
      }
   }

   public function cp()
   {
      if ($this->tongren_CP_tag_id != 0){
         return Tag::find($this->tongren_CP_tag_id)->tag_explanation;
      }
   }
}
