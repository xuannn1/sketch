<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Auth;
use GrahamCampbell\Markdown\Facades\Markdown;
use Genert\BBCode\BBCode;
use App\Models\Channel;
use App\Models\Label;
use App\Models\Tag;
use App\Models\Xianyu;
use App\Models\Quiz;
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

    public static function allChannels()//获得站上所有的channel
    {
        return Cache::remember('allChannels', 10, function (){
            return Channel::orderBy('orderBy','asc')->get();
        });
    }

    public static function allLabels()//获得站上所有的label
    {
        return Cache::remember('allLabels', 10, function (){
            return Label::all();
        });
    }

    public static function xianyus($thread_id)//获得站上某thread所有咸鱼列表
    {
        return Cache::remember('xianyus-'.$thread_id, 10, function () use($thread_id){
            return Xianyu::where('thread_id','=', $thread_id)->with('creator')->orderBy('created_at','desc')->limit(100)->get();
        });
    }

    public static function alltags()//获得站上所有的label
    {
        return Cache::remember('allTags', 10, function (){
            return Tag::all();
        });
    }

    public static function system_variable()//获得当前系统数据
    {
        return Cache::remember('system_variable', 10, function () {
            return DB::table('system_variables')->first();
        });
    }

    public static function labels_yuanchuang(){
        return Helper::allLabels()->where('channel_id',1);
    }

    public static function labels_tongren(){
        return Helper::allLabels()->where('channel_id',2);
    }

    public static function tags_general(){
        return Helper::allTags()
        ->whereIn('tag_group',[0,5,25])
        ->sortByDesc('books')
        ->sortBy('tag_info');
    }

    public static function tags_feibianyuan(){
        return Helper::allTags()
        ->where('tag_group',0)
        ->sortByDesc('books')
        ->sortBy('tag_info');
    }

    public static function tags_bianyuan(){
        return Helper::allTags()
        ->where('tag_group',5)
        ->sortByDesc('books')
        ->sortBy('tag_info');
    }

    public static function tags_tongren(){
        return Helper::allTags()
        ->where('tag_group',25)
        ->sortByDesc('books');
    }

    public static function tags_tongren_yuanzhu(){
        return Helper::allTags()
        ->where('tag_group',10)
        ->sortByDesc('books');
    }

    public static function tags_tongren_cp(){
        return Helper::allTags()
        ->where('tag_group',20)
        ->sortByDesc('books');
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

public static function convertBBCodetoMarkdown($string){//其实反过来的。。
    $string = Markdown::convertFromHtml($string);
    $string = BBCode::convertToHtml($string);
    return $string;
}

public static function random_quizzes($level=0)
{
    return Cache::remember('random_quizzes'.$level, 10, function () use ($level) {
        return Quiz::with('random_options')->where('quiz_level','=',$level)->inRandomOrder()->take(config('constants.quiz_test_number'))->get();
    });
}

public static function all_quiz_answers()
{
    return Cache::remember('all_quiz_answers', 20, function() {
        return DB::table('quiz_options')->select('id', 'quiz_id', 'is_correct')->get();
    });
}

public static function find_quiz_set($quiz_id)
{
    return Cache::remember('quiz-'.$quiz_id, 20, function() use($quiz_id) {
        $quiz = Quiz::with('quiz_options')->find($quiz_id);
        return $quiz;
    });
}

public static function find_tag_by_name($tagname)
{
    return Cache::remember('tagname-'.$tagname, 20, function() use($tagname) {
        return $tag = self::alltags()->keyBy('tag_name')->get($tagname);
    });
}

public static function find_tag_by_id($tagid)
{
    return Cache::remember('tagid-'.$tagid, 20, function() use($tagid) {
        return $tag = self::alltags()->keyBy('id')->get($tagid);
    });
}

public function find_tag_by_label_id($labelid)
{
    return Cache::remember('tagbylabelid-'.$labelid, 20, function() use($labelid) {
        $label_tag = null;
        $label = Helper::allLabels()->keyBy('id')->get($label_id);
        $label_tag = Helper::alltags()->keyBy('tag_name')->get($label->labelname);
        return $label_tag;
    });
}


}
