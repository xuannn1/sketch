<?php

namespace App\Helpers;
use App\Models\Tag;
use App\Models\Title;
use Cache;
use DB;

class ConstantObjects
{
    protected static $channel_types = array('book', 'thread', 'request', 'homework', 'list'); // channel的分类类别

    public static function allChannels()//获得站上所有的channel
    {
        return collect(config('channel'));//将channels转化成collection
    }

    public static function publicChannelTypes($type='')
    {
        if (in_array($type, self::$channel_types)){
            return self::allChannels()->where('type', $type)->where('is_public', true)->pluck('id')->toArray();
        }
        return [];
    }

    public static function channelTypes($type='')
    {
        if (in_array($type, self::$channel_types)){
            return self::allChannels()->where('type', $type)->pluck('id')->toArray();
        }
        return [];
    }

    public static function public_channels()
    {
        return self::allChannels()->where('is_public', true)->pluck('id')->toArray();
    }

    public static function allTags()//获得站上所有的tags
    {
        return Cache::remember('allTags', 10, function (){
            return Tag::all();
        });
    }

    public static function organizeBookTags()//获得书籍必备的tag列表
    {
        $book_length_tags = self::noTongrenTags()->where('tag_type','=','篇幅');
        $book_status_tags = self::noTongrenTags()->where('tag_type','=','进度');
        $sexual_orientation_tags = self::noTongrenTags()->where('tag_type','=','性向');
        $editor_tags = self::noTongrenTags()->where('tag_type','=','编推');
        return [
            'book_length_tags' => $book_length_tags,
            'book_status_tags' => $book_status_tags,
            'sexual_orientation_tags' => $sexual_orientation_tags,
            'editor_tags' => $editor_tags,
        ];
    }

    public static function noTongrenTags()//获得站上非同人的所有的tags
    {
        return Cache::remember('noTongrenTags', 10, function (){
            return Tag::whereNotIn('tag_type', ['同人原著', '同人CP'])->get();
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
            return self::alltags()->keyBy('id')->get($tagid);
        });
    }

    public static function decodeSelectedTags($tags='')
    {
        $located_tags = [];
        if($tags){
            $tag_ids = json_decode($tags);
            foreach($tag_ids as $id){
                if($id){
                    $tag = self::find_tag_by_id($id);
                    if($tag){
                        array_push($located_tags, $tag);
                    }
                }
            }
        }
        return $located_tags;
    }

    public static function titles()//获得站上所有的titles
    {
        return Cache::remember('titles', 10, function (){
            return Title::all();
        });
    }

    public static function system_variable()//获得当前系统数据
    {
        return Cache::remember('system_variable', 10, function () {
            return DB::table('system_variables')->first();
        });
    }

    public static function firewall_ips()//获得当前被屏蔽的ip地址列表
    {
        return Cache::remember('firewall_ips', 10, function () {
            return DB::table('firewall')->where('is_valid',true)->pluck('ip_address')->toArray();
        });

    }

}
