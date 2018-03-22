<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GrahamCampbell\Markdown\Facades\Markdown;
use App\Models\User;
use App\Models\PostComment;
use App\Shengfan;
use App\Models\Thread;
use App\Chapter;
use App\LongComment;
use Illuminate\Support\Facades\Config;


class Post extends Model
{
   use SoftDeletes;
   use Searchable;
   protected $dates = ['deleted_at'];

   protected $guarded = [];

    public function sub_str($str, $length = 0, $append = true)
    {
        $str = trim($str);
        $strlength = strlen($str);
        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }
        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        } else {
            $newstr = substr($str, 0, $length);
        }
        if ($append && $str != $newstr) {
            $newstr .= '...';
        }
        return $newstr;
    }

   public function owner()
   {
     return $this->belongsTo(User::class, 'user_id')->select(['id','name'])->withDefault();
   }

   public function thread()
   {
      return $this->belongsTo(Thread::class, 'thread_id')->withDefault();
   }

   public function comments()
   {
      return $this->hasMany(PostComment::class)->orderBy('created_at','desc');
   }
   public function allcomments()
   {
      return $this->hasMany(PostComment::class)->orderBy('created_at','desc');
   }

   public function shengfan_voted(User $user)
   {
      return (Shengfan::where('post_id', $this->id)->where('user_id', $user->id)->first());
   }
   public function reply_to_post()
   {
      return $this->belongsTo(Post::class, 'reply_to_post_id')->withDefault();
   }
   public function chapter()
   {
      return $this->belongsTo(Chapter::class, 'chapter_id')->withDefault();
   }
   public function trim($str, $len)
   {
      $body = preg_replace('/[[:punct:]\s\n\t\r]/','',$str);
      $body = trim($body);
      if (strlen($body)>$len){
         return $this->sub_str($body, $len, true);
      }else{
         return $body;
      }
   }
   public function checklongcomment()
   {
      if (!$this->maintext){//必须不能是某章节正文
         $string = preg_replace('/[[:punct:]\s\n\t\r]/','',$this->body);
         $lenth = iconv_strlen($string, 'utf-8');
         if($lenth>=Config::get('constants.longcomment_lenth')){
            $this->long_comment = true;
            if ($this->long_comment_id ==0){
               $longcomment = LongComment::create([
                  'post_id' => $this->id,
               ]);
               $this->long_comment_id = $longcomment->id;
            }
            $this->save();
         }
      }
      return;
   }

   public function searchableAs()
    {
        return 'posts_index';
    }
   public function toSearchableArray()
   {
       $array = $this->only('title','body');
       return $array;
   }
}
