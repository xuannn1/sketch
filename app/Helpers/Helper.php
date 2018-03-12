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
   public static function wrapText($post= null)//自己写编辑器
   {
      while(strip_tags($post)!=$post){
         $post = strip_tags($post);
      }
      $post = str_replace("\r\n", "\n", $post);
      $post = str_replace("\r", "\n", $post);
      $post = preg_replace('/\n{3,}/', "</p><br><p>", $post);
      $post = preg_replace('/\n{1,2}/', "</p><p>", $post);
      $post = "<p>{$post}</p>";
      $post = str_replace(
          array("[b]","[/b]","[u]","[/u]","[em]","[/em]","[blockquote]","[/blockquote]","[h6]","[/h6]","[h5]","[/h5]","[h4]","[/h4]","[h3]","[/h3]","[h2]","[/h2]","[h1]","[/h1]","[code]","[/code]","[linebreak]"),
          array("<b>", "</b>","<u>","</u>","<em>","</em>","<blockquote>","</blockquote>","<h6>","</h6>","<h5>","</h5>","<h4>","</h4>","<h3>","</h3>","<h2>","</h2>","<h1>","</h1>","<code>","</code>","<hr>"),
          $post
      );
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
