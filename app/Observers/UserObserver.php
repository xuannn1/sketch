<?php
namespace App\Observers;

use Cache;
use App\Models\User;

/**
 * User observer
 */
class UserObserver
{
    public function updated(User $user)
    {
        Cache::put("cachedUser.{$user->id}", $user, 15);
    }

}
