<?php
namespace App\Observers;

use Cache;
use App\Models\UserInfo;

/**
 * User observer
 */
class UserInfoObserver
{
    public function updated(UserInfo $userinfo)
    {
        Cache::put("cachedUserInfo.{$userinfo->user_id}", $userinfo, 15);
    }
}
