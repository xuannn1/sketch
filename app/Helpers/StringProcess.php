<?php

namespace App\Helpers;

use GrahamCampbell\Markdown\Facades\Markdown;
use Genert\BBCode\BBCode;

class StringProcess
{

    public static function simpletrim($text=null, int $len)//截取一个特定长度的字串
    {
        $substr = trim(iconv_substr($text, 0, $len, 'utf-8'));
        if(iconv_strlen($text) > iconv_strlen($substr)){
            $substr.='…';
        }
        return $substr;
    }

    public static function trimSpaces($text=null)//去掉输入的一段文字里，多余的html-tag，多余的换行，和每段开头多余的空格
    {
        //去除内容中的html-tag（前端需要对html-tag类似物进行提醒）
        while(strip_tags($text,"<br>")!=$text){
            $text = strip_tags($text,"<br>");
        }
        //去除多余的段落间空行
        $lines = preg_split("/(\r\n|\n|\r)/",$text);
        $text = "";
        //去除每一行开头的中英文半全角字符空格
        foreach ($lines as $line){
            $line = mb_ereg_replace('^(　| )+', '', $line);
            if($line){
                $text.= $line."\n";
            }
        }
        return $text;
    }

    public static function trimtext($text=null, int $len)//截取一个特定长度的字串,去除内部的bbcode和htmltag
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

    public static function htmltotext($post= null) //下载专用的转格式
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

    public static function wrapParagraphs($post= null)
    {
        $post = self::trimSpaces($post);
        $bbCode = new BBCode();
        $bbCode = self::addCustomParserBBCode($bbCode);
        $post = $bbCode->convertToHtml($post);
        $post = str_replace("<br>", "</p><br><p>", $post);
        $post = preg_replace('/\n{1,}/', "</p><p>", $post);
        if($post){
            $post = "<p>{$post}</p>";
        }
        return $post;
    }

    public static function convertBBCodetoMarkdown($string){//其实反过来的。。
        $string = Markdown::convertFromHtml($string);
        $string = BBCode::convertToHtml($string);
        return $string;
    }

    public static function convert_to_public($string= null)
    {
        $badstring = config('forbiddenwords.not_in_public');
        $newstring = preg_replace("/$badstring/i",'',$string);
        if (strcmp($newstring, $string) === 0){
            return $newstring;
        }else{
            return self::convert_to_public($newstring);
        }
    }

    public static function convert_to_title($string= null)
    {
        $badstring = config('forbiddenwords.not_in_title');
        $newstring = preg_replace("/$badstring/i",'', self::convert_to_public($string));
        if (strcmp($newstring, $string) === 0){
            return $newstring;
        }else{
            return self::convert_to_title($newstring);
        }
    }

    public static function wrapSpan($post= null)
    {
        $post = self::trimSpaces($post);
        $bbCode = new BBCode();
        $bbCode = self::addCustomParserBBCode($bbCode);
        $post = $bbCode->convertToHtml($post);
        $post = preg_replace('/\n{1,}/', "<br>", $post);
        $post = "{$post}";
        return $post;
    }

    public static function add_to_thread_filter($filter, $request_all)
    {
        $finalfilter = [];
        $selectors = ['inChannel', 'isPublic', 'inPublicChannel', 'withType', 'withBianyuan', 'withTag', 'excludeTag', 'ordered'];
        foreach($selectors as $selector){
            if(array_key_exists($selector, $request_all)&&($selector!=key($filter))){
                $finalfilter =  array_merge([$selector=>$request_all[$selector]], $finalfilter);
            }
        }
        $finalfilter = array_merge($filter, $finalfilter);
        return $finalfilter;
    }

    public static function remove_from_thread_filter($filter_key, $request_all)
    {
        $finalfilter = [];
        $selectors = ['inChannel', 'isPublic', 'inPublicChannel', 'withType', 'withBianyuan', 'withTag', 'excludeTag', 'ordered'];
        foreach($selectors as $selector){
            if(array_key_exists($selector, $request_all)&&($selector!=$filter_key)){
                $finalfilter =  array_merge([$selector=>$request_all[$selector]], $finalfilter);
            }
        }
        return $finalfilter;
    }

    public static function mergeWithTag($id, $originalfilter=[])
    {
        $finalfilter=[];
        foreach($originalfilter as $key=>$filter){
            if($key!='withTag'){
                $finalfilter=array_merge([$key=>$filter], $finalfilter);
            }
        }
        $withTag=(string)$id;
        if(array_key_exists('withTag',$originalfilter)){
            $withTag=self::concatenate_with_char('-',$originalfilter['withTag'],(string)$id);
        }
        return array_merge(['withTag'=>$withTag], $finalfilter);
    }

    public static function removeWithTag($id, $originalfilter='')
    {
        $finalfilter=[];
        foreach($originalfilter as $key=>$filter){
            if($key!='withTag'){
                $finalfilter=array_merge([$key=>$filter], $finalfilter);
            }
        }

        if(array_key_exists('withTag',$originalfilter)){
            $withTag='';
            $oldWithTag = $originalfilter['withTag'];
            $oldAndTags = explode('-',$oldWithTag);
            foreach($oldAndTags as $oldAndTag){
                $oldOrTags = explode('_',$oldAndTag);
                $orTag='';
                foreach($oldOrTags as $oldOrTag){
                    if($oldOrTag!=$id){
                        $orTag=self::concatenate_with_char('_',$orTag,$oldOrTag);
                    }
                }
                $withTag=self::concatenate_with_char('-',$withTag,$orTag);
            }
            if($oldWithTag&&$withTag){
                $finalfilter=array_merge(['withTag'=>$withTag], $finalfilter);
            }
        }
        return $finalfilter;
    }

    public static function removeExcludeTag($id, $originalfilter='')
    {
        $finalfilter=[];
        foreach($originalfilter as $key=>$filter){
            if($key!='withTag'){
                $finalfilter=array_merge([$key=>$filter], $finalfilter);
            }
        }

        if(array_key_exists('excludeTag',$originalfilter)){
            $excludeTag='';
            $oldexcludeTags = explode('-',$originalfilter['excludeTag']);
            foreach($oldexcludeTags as $oldexcludeTag){
                if($oldexcludeTag!=$id){
                    $excludeTag=self::concatenate_with_char('_',$excludeTag,$oldexcludeTag);
                }
            }
            if($excludeTag){
                $finalfilter=array_merge(['excludeTag'=>$excludeTag], $finalfilter);
            }
        }
        return $finalfilter;
    }

    public static function concatenate_with_char($char='',$a='',$b='')
    {
        if($a&&$b){
            return $a.$char.$b;
        }
        if($a){
            return $a;
        }
        if($b){
            return $b;
        }
        return;
    }

    public static function concatenate_andTag($array=[], $withTag='')
    {
        $andTag='';
        foreach($array as $tag){
            if($tag!=0){
                $andTag=StringProcess::concatenate_with_char('_',$andTag,$tag);
            }
        }
        return StringProcess::concatenate_with_char('-',$withTag,$andTag);
    }

    public static function concatenate_excludeTag($array=[], $withTag='')
    {
        $excludeTag='';
        foreach($array as $tag){
            if($tag!=0){
                $excludeTag=StringProcess::concatenate_with_char('-',$excludeTag,(string)$tag);
            }
        }
        return $excludeTag;
    }

    public static function concatenate_channels($array=[])
    {
        $inChannel='';
        foreach($array as $channel_id){
            if($channel_id!=0){
                $inChannel=StringProcess::concatenate_with_char('-',$inChannel, $channel_id);
            }
        }
        return $inChannel;
    }

    public static function removeInChannel($id, $originalfilter='')
    {
        $finalfilter=[];
        foreach($originalfilter as $key=>$filter){
            if($key!='inChannel'){
                $finalfilter=array_merge([$key=>$filter], $finalfilter);
            }
        }

        if(array_key_exists('inChannel',$originalfilter)){
            $inChannel='';
            $oldInChannel = explode('-',$originalfilter['inChannel']);
            foreach($oldInChannel as $oldChannel){
                if($oldChannel!=$id){
                    $inChannel=self::concatenate_with_char('-',$oldChannel,$inChannel);
                }
            }
            if($inChannel){
                $finalfilter=array_merge(['inChannel'=>$inChannel], $finalfilter);
            }
        }
        return $finalfilter;
    }

}
