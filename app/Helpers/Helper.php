<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Auth;
use GrahamCampbell\Markdown\Facades\Markdown;
use Genert\BBCode\BBCode;

class Helper
{
   // public static function wrapParagraphs($post= null)
   // {
   //    while(strip_tags($post,"<br>")!=$post){
   //       $post = strip_tags($post,"<br>");
   //    }
   //    $post = str_replace("\r\n", "\n", $post);
   //    $post = str_replace("\r", "\n", $post);
   //    $post = preg_replace('/\n{1,}/', "</p><p>", $post);
   //    $post = "<p>{$post}</p>";
   //    return $post;
   // }

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
   public static function htmltotext($post= null)
   {
      $post = str_replace("</p>", "\n", $post);
      $post = str_replace("<br>", "\n", $post);
      while(strip_tags($post)!=$post){
         $post = strip_tags($post);
      }
      return $post;
   }


   public static function sosadMarkdown($post= null)
   {
      $post = Markdown::convertToHtml($post);
      $post = str_replace("</p>\n", "</p>", $post);
      $post = str_replace("\n", "</p><p>", $post);
      return $post;
   }
//test bbcodeparser
   public static function wrapParagraphs($post= null)
   {
       while(strip_tags($post,"<br>")!=$post){
          $post = strip_tags($post,"<br>");
       }
       $post = str_replace("\r\n", "\n", $post);
       $post = str_replace("\r", "\n", $post);


       $bbCode = new BBCode();
       $bbCode->addParser(
            'blockquote',
            '/\[blockquote\](.*?)\[\/blockquote\]/s',
            '<blockquote>$1</blockquote>',
            '$1'
        );
        $bbCode->addParser(
             'unordered list',
             '/\[ul\](.*?)\[\/ul\]/s',
             '<ul>$1</ul>',
             '$1'
        );
        $bbCode->addParser(
             'line breaker',
             '/\[br\]/s',
             '<br>',
             ''
        );
        $bbCode->addParser(
            'ordered list',
            '/\[ol\](.*?)\[\/ol\]/s',
            '<ol>$1</ol>',
            '$1'
        );
        $bbCode->addParser(
           'list',
            '/\[li\](.*?)\[\/li\]/s',
            '<li>$1</li>',
            '$1'
        );
        $bbCode->addParser(
            'size',
            '/\[size\=(.*?)\](.*?)\[\/size\]/s',
            '<span style="font-size:$1px">$2</span>',
            '$1'
        );
        $bbCode->addParser(
            'color',
            '/\[color\=(.*?)\](.*?)\[\/color\]/s',
            '<span style="color:$1">$2</span>',
            '$1'
        );
        $bbCode->addParser(
            'highlight',
            '/\[highlight\=(.*?)\](.*?)\[\/highlight\]/s',
            '<span style="background-color:$1">$2</span>',
            '$1'
        );

       $post = $bbCode->convertToHtml($post);

       $post = str_replace("<br>", "</p><br><p>", $post);
       $post = preg_replace('/\n{1,}/', "</p><p>", $post);
       $post = "<p>{$post}</p>";
       return $post;
   }

   public static function convert_to_public($string= null)
   {
       $badstring="|骚浪|骚浪贱|NP|np|Np|nP|冰恋|高H|高h|强制爱|处男|处女|恋童癖|恋童|3P|骑乘|play|纯肉|滥交|NTR|性癖|扶她|扶他|自慰|强上|啪啪啪|调♂教|调教|鸡巴|J8|撸|双性|产子|♂|淫荡|荡妇|爱液|按摩棒|拔出来|爆草|暴干|暴奸|暴乳|爆乳|暴淫|被操|被插|被干|逼奸|插暴|插爆|操逼|肏|潮吹|抽插|抽送|后穴|淫液|操烂|吞精|春药|发浪|发骚|粉穴|菊穴|干死你|肛交|肛门|龟头|AV|GV|巨屌|口爆|口暴|口活|口交|狂操|浪叫|凌辱|乱交|乱伦|裸陪|轮暴|轮奸|迷奸|强暴|全裸|人妻|人兽|肉棒|肉具|骚逼|骚水|乳交|乳沟|射颜|颜射|熟女|调教|小穴|小逼|性交|性奴|性虐|胸推|穴口|阳具|体位|舔脚|文爱|文做|要射了|淫贱|淫媚|淫糜|援交|欲火|QJ|qj|lj|LJ|lt|LT|幼幼|TJ|BDSM";

       return preg_replace("/$badstring/i",'',$string);
   }

   public static function convert_to_title($string= null)
   {
       $badstring="|《|》|【|】|\[|\]|X|\/|\\|╳|";
       return preg_replace("/$badstring/i",'',Helper::convert_to_public($string));
   }
}
