<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserInfo;
use App\Observers\UserObserver;
use App\Observers\UserInfoObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;
use Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Carbon::setLocale('zh');
        User::observe(UserObserver::class);
        UserInfo::observe(UserInfoObserver::class);
        
        Relation::morphMap([
            'post' => 'App\Models\Post',
            'quote' => 'App\Models\Quote',
            'status' => 'App\Models\Status',
            'thread' => 'App\Models\Thread',
        ]);
    }

    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        if ($this->app->isLocal()){
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
