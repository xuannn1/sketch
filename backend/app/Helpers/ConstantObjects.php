<?php

namespace App\Helpers;
use App\Models\Channel;
use App\Models\Tag;
use Cache;
use DB;

class ConstantObjects
{

    public static function allChannels()//获得站上所有的channel
    {
        return Cache::remember('allChannels', 10, function (){
            return Channel::orderBy('order_by','asc')->get();
        });
    }
    public static function book_channels()
    {
        return Cache::remember('book_channels', 10, function (){
            return self::allChannels()->where('is_book', true)->where('is_public', true)->pluck('id')->toArray();
        });
    }
    public static function none_book_channels()
    {
        return Cache::remember('none_book_channels', 10, function (){
            return self::allChannels()->where('is_book', false)->where('is_public', true)->pluck('id')->toArray();
        });
    }

    public static function allTags()//获得站上所有的tags
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

    public static function firewall_ips()//获得当前被屏蔽的ip地址列表
    {
        return Cache::remember('firewall_ips', 10, function () {
            return DB::table('firewall')->where('is_valid',true)->pluck('ip_address')->toArray();
        });

    }

    public static function role_users()//目前系统所保存的所有现在在使用中的特殊的用户身份列表，如是否admin，editor，no-logging，no-posting, channel_admin, tag_admin
    {
        return Cache::remember('role_users', 10, function () {
            return DB::table('role_user')->where('is_valid', true)->select(['user_id','role', 'options'])->get();
        });
    }
}
