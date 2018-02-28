<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Auth;

class Helper
{
   public static function wrapParagraphs($post= null)
   {
      while(strip_tags($post)!=$post){
         $post = strip_tags($post);
      }
      $post = str_replace("\r\n", "\n", $post);
      $post = str_replace("\r", "\n", $post);
      $post = preg_replace('/\n{2,}/', "</p><br><p>", $post);
      $post = preg_replace('/\n/', '</p><p>',$post);
      $post = "<p>{$post}</p>";
      //$post = nl2br($post, false);
      //$post = '<p>' . preg_replace('#(<br>[\r\n]+){2}#', '</p><p>', $post) . '</p>';
      return $post;
   }
   public static function trimtext($text, int $len)
   {
      $str = preg_replace('/[[:punct:]\s\n\t\r]/','',$text);
      $substr = iconv_substr($str, 0, $len, 'utf-8');
      if(iconv_strlen($str) > iconv_strlen($substr)){
         $substr.='…';
      }
      return $substr;
   }

   public static function clearcache()
   {
      if(Cache::has(Auth::id() . 'new')){
         Cache::forget(Auth::id() . 'new');
      }
      if(Cache::has(Auth::id() . 'old')){
         Cache::forget(Auth::id() . 'old');
      }
      return true;
   }

}
