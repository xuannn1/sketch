<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Cache;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserIntro;
use Auth;

class CacheUser{ //cache-user class
    public static function user($id){
        if(!$id||$id<=0){return;}

        return Cache::remember('cachedUser.'.$id, 10, function() use($id) {
            $user = User::find($id);
            $user->load('title');
            return $user;
        });
    }

    public static function info($id){
        if(!$id||$id<=0){return;}

        return Cache::remember('cachedUserInfo.'.$id, 10, function() use($id) {
            return UserInfo::find($id);
        });
    }

    public static function clearuser($id)
    {
        Cache::pull('cachedUser.'.$id);
        Cache::pull('cachedUserInfo.'.$id);
    }

    public static function intro($id){
        if(!$id||$id<=0){return;}

        return Cache::remember('cachedUserIntro.'.$id, 10, function() use($id) {
            return UserIntro::find($id);
        });
    }

    public static function clear_intro($id){
        Cache::pull('cachedUserIntro.'.$id);
    }

    public static function AUser(){
        return self::user(auth()->check()?auth()->id():0);
    }
    public static function AInfo(){
        return self::info(auth()->check()?auth()->id():0);
    }
    public static function AIntro(){
        return self::intro(auth()->check()?auth()->id():0);
    }
}
