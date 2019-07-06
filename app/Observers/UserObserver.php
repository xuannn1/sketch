<?php
namespace App\Observers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Cache;

/**
 * User observer
 */
class UserObserver
{
    /**
     * @param User $user
     */
    public function saved(User $user)
    {
        Cache::put("user-auth.{$user->id}", $user, 60);
    }

    /**
     * @param User $user
     */
    public function deleted(User $user)
    {
        Cache::forget("user-auth.{$user->id}");
    }

    /**
     * @param User $user
     */
    public function restored(User $user)
    {
        Cache::put("user-auth.{$user->id}", $user, 60);
    }

    /**
     * @param User $user
     */
    public function retrieved(User $user)
    {
        Cache::add("user-auth.{$user->id}", $user, 60);
    }
}
