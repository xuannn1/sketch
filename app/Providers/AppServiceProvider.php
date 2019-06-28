<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
//use App\Models\User;
//use App\Observers\UserObserver;
use Illuminate\Support\Facades\Schema;
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
         //User::observe(UserObserver::class);
         Schema::defaultStringLength(191);
         Carbon::setLocale('zh');
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
