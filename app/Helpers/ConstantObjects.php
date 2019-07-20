<?php

namespace App\Helpers;
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

    public static function primary_tags_in_channel($id)
    {
        return Cache::remember('primary_tags_in_channel.'.$id, 10, function () use($id){
            $tags = \App\Models\Tag::where('is_primary','=',1)->where('channel_id','=',$id)->get();
            if($id==1){
                $extraTags = \App\Models\Tag::where('is_primary','=',1)->where('channel_id','=',0)->get();
                $tags = $tags->merge($extraTags);
            }
            return $tags;
        });
    }

    public static function extra_primary_tags_in_channel($id)
    {
        return Cache::remember('primary_tags_of_channel.'.$id, 10, function () use($id) {
            $tags = self::primary_tags_in_channel($id);
            if($id==1){
                $extraTags = \App\Models\Tag::where('is_primary','=',1)->where('channel_id','=',0)->get();
                $tags = $tags->merge($extraTags);
            }
            if($id<=2){
                $extraTags = \App\Models\Tag::where('tag_type','=','性向')->get();
                $tags = $tags->merge($extraTags);
            }
            return $tags;
        });
    }

    public static function allTags()//获得站上所有的tags
    {
        return Cache::remember('allTags', 10, function (){
            return \App\Models\Tag::all();
        });
    }

    public static function noTongrenTags()//获得站上非同人的所有的tags
    {
        return Cache::remember('noTongrenTags', 10, function (){
            return \App\Models\Tag::whereNotIn('tag_type', ['同人原著', '同人CP'])->get();
        });
    }

    public static function tongren_yuanzhu_tags()
    {
        return Cache::remember('TongrenYuanzhuTags', 10, function (){
            return \App\Models\Tag::where('tag_type', '同人原著')->get();
        });
    }

    public static function tongren_CP_tags()
    {
        return Cache::remember('TongrenCPTags', 10, function (){
            return \App\Models\Tag::where('tag_type', '同人CP')->get();
        });
    }

    public static function book_custom_tags()
    {
        return Cache::remember('bookCustomTags', 10, function (){
            return \App\Models\Tag::whereIn('tag_type', config('tag.custom_tag_types'))->where('channel_id','<=',2)->get();
        });
    }

    public static function organizeBookCreationTags()//获得书籍创建必备的tag列表
    {
        return Cache::remember('organizeBookCreationTags', 10, function (){
            $yuanchuang_primary_tags = self::primary_tags_in_channel(1);
            $tongren_primary_tags = self::primary_tags_in_channel(2);
            $tongren_yuanzhu_tags = self::tongren_yuanzhu_tags();
            $tongren_CP_tags = self::tongren_CP_tags();
            $book_length_tags = self::noTongrenTags()->where('tag_type','=','篇幅');
            $book_status_tags = self::noTongrenTags()->where('tag_type','=','进度');
            $sexual_orientation_tags = self::noTongrenTags()->where('tag_type','=','性向');
            $book_custom_Tags = self::book_custom_tags()->sortByDesc('tag_type');
            return [
                'yuanchuang_primary_tags' => $yuanchuang_primary_tags,
                'tongren_primary_tags' => $tongren_primary_tags,
                'tongren_yuanzhu_tags' => $tongren_yuanzhu_tags,
                'tongren_CP_tags' => $tongren_CP_tags,
                'book_length_tags' => $book_length_tags,
                'book_status_tags' => $book_status_tags,
                'sexual_orientation_tags' => $sexual_orientation_tags,
                'book_custom_Tags' => $book_custom_Tags,
            ];
        });

    }

    public static function organizeBasicBookTags()//获得书籍tag修改、站内总体标签列表中，必备的tag列表——含同人原著，不含同人CP
    {
        return Cache::remember('organizeBasicBookTags', 10, function (){
            $tongren_yuanzhu_tags = self::tongren_yuanzhu_tags();
            $book_length_tags = self::noTongrenTags()->where('tag_type','=','篇幅');
            $book_status_tags = self::noTongrenTags()->where('tag_type','=','进度');
            $sexual_orientation_tags = self::noTongrenTags()->where('tag_type','=','性向');
            $editor_tags = self::noTongrenTags()->where('tag_type','=','编推');
            $book_custom_Tags = self::book_custom_tags()->sortByDesc('tag_type');
            return [
                'tongren_yuanzhu_tags' => $tongren_yuanzhu_tags,
                'book_length_tags' => $book_length_tags,
                'book_status_tags' => $book_status_tags,
                'sexual_orientation_tags' => $sexual_orientation_tags,
                'editor_tags' => $editor_tags,
                'book_custom_Tags' => $book_custom_Tags,
            ];
        });

    }


    public static function find_tag_by_name($tagname)
    {
        return Cache::remember('tagname-'.$tagname, 10, function() use($tagname) {
            return $tag = \App\Models\Tag::where('tag_name', $tagname)->first();
        });
    }

    public static function find_tag_by_id($tagid)
    {
        return Cache::remember('tagid-'.$tagid, 10, function() use($tagid) {
            return $tag = \App\Models\Tag::where('id', $tagid)->first();
        });
    }

    public static function find_tag_by_parent_id($tagid)
    {
        return Cache::remember('tagparentid-'.$tagid, 10, function() use($tagid) {
            return $tag = \App\Models\Tag::where('parent_id', $tagid)->get();
        });
    }


    public static function titles()//获得站上所有的titles
    {
        return Cache::remember('titles', 20, function (){
            return \App\Models\Title::all();
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
