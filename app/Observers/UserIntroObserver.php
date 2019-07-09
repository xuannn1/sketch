<?php
namespace App\Observers;

use Cache;
use App\Models\UserIntro;

/**
 * User observer
 */
class UserIntroObserver
{
    public function updated(UserIntro $userintro)
    {
        Cache::put("cachedUserIntro.{$userintro->user_id}", $userintro, 60);
    }
}
