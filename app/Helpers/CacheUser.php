<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Cache;
use App\Models\User;
use App\Models\UserInfo;

class CacheUser{
    public static function findCachedUser($id){
        return Cache::remember('cachedUser.'.$id, 60, function() use($id) {
            return User::find($id);
        });
    }
    public static function findCachedUserInfo($id){
        return Cache::remember('cachedUserInfo.'.$id, 60, function() use($id) {
            return UserInfo::find($id);
        });
    }
}
