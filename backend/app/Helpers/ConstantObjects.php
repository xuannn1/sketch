<?php

namespace App\Helpers;
use App\Models\Channel;
use App\Models\Label;
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

    public static function allLabels()//获得站上所有的label
    {
        return Cache::remember('allLabels', 10, function (){
            return Label::all();
        });
    }
    public static function allTags()//获得站上所有的label
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

}
