<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Auth;
use GrahamCampbell\Markdown\Facades\Markdown;
use Genert\BBCode\BBCode;

class Helper
{
   public static function trimtext($text, int $len)
   {
      $str = preg_replace('/[[:punct:]\s\n\t\r]/','',$text);
      $substr = trim(iconv_substr($str, 0, $len, 'utf-8'));
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
        $bbCode->addParser(
            'table',
            '/\[table\](.*?)\[\/table\]/s',
            '<table>$1</table>',
            '$1'
        );
        $bbCode->addParser(
            'table tr',
            '/\[tr\](.*?)\[\/tr\]/s',
            '<tr>$1</tr>',
            '$1'
        );
        $bbCode->addParser(
            'table td',
            '/\[td\](.*?)\[\/td\]/s',
            '<td>$1</td>',
            '$1'
        );
        $bbCode->addParser(
            'table th',
            '/\[th\](.*?)\[\/th\]/s',
            '<th>$1</th>',
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
       $badstring = config('constants.word_filter.not_in_public');
       return preg_replace("/$badstring/i",'',$string);
   }

   public static function convert_to_title($string= null)
   {
       $badstring = config('constants.word_filter.not_in_title');
       $string = preg_replace("/$badstring/i",'',Helper::convert_to_public($string));
       return $string;
   }

   public static function convertBBCodetoMarkdown($string){
       $string = Markdown::convertFromHtml($string);
       $string = BBCode::convertToHtml($string);
       return $string;
   }
}
