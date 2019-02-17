<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Auth;
use GrahamCampbell\Markdown\Facades\Markdown;
use Genert\BBCode\BBCode;
use App\Models\Tag;
use Cache;
use DB;

class Helper
{
    public static function trimtext($text=null, int $len)//截取一个特定长度的字串
    {
        $bbCode = new BBCode();
        $bbCode = self::addCustomParserBBCode($bbCode);

        $text = self::trimSpaces($text);//去除字串中多余的空行，html-tag，每一段开头的空格
        $text = $bbCode->stripBBCodeTags((string) $text);
        $text = preg_replace('/[[:punct:]\s\n\t\r]/',' ',$text);
        $substr = trim(iconv_substr($text, 0, $len, 'utf-8'));
        if(iconv_strlen($text) > iconv_strlen($substr)){
            $substr.='…';
        }
        return $substr;
    }


    public static function trimSpaces($text=null)//去掉输入的一段文字里，多余的html-tag，和每段开头多余的空格，和多余的换行
    {
        while(strip_tags($text,"<br>")!=$text){
            $text = strip_tags($text,"<br>");
        }
        $lines = preg_split("/(\r\n|\n|\r)/",$text);
        $text = "";
        foreach ($lines as $line){
            $line = mb_ereg_replace('(^(　| )+|(　| )+$)', '', $line);
            if($line){
                $text.= $line."\n";
            }
        }
        return $text;
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

    public static function addCustomParserBBCode($bbCode){
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
        return $bbCode;
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
        $post = self::trimSpaces($post);
        $bbCode = new BBCode();
        $bbCode = self::addCustomParserBBCode($bbCode);
        $post = $bbCode->convertToHtml($post);
        $post = str_replace("<br>", "</p><br><p>", $post);
        $post = preg_replace('/\n{1,}/', "</p><p>", $post);
        $post = "<p>{$post}</p>";
        return $post;
    }

    public static function convert_to_public($string= null)
    {
        $badstring = config('constants.word_filter.not_in_public');
        $newstring = preg_replace("/$badstring/i",'',$string);
        if (strcmp($newstring, $string) === 0){
            return $newstring;
        }else{
            return self::convert_to_public($newstring);
        }
    }

    public static function convert_to_title($string= null)
    {
        $badstring = config('constants.word_filter.not_in_title');
        $newstring = preg_replace("/$badstring/i",'', self::convert_to_public($string));
        if (strcmp($newstring, $string) === 0){
            return $newstring;
        }else{
            return self::convert_to_title($newstring);
        }
    }

    public static function convertBBCodetoMarkdown($string){//其实反过来的。。
        $string = Markdown::convertFromHtml($string);
        $string = BBCode::convertToHtml($string);
        return $string;
    }
}
