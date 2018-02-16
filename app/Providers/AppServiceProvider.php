<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
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
         Schema::defaultStringLength(191);
         Carbon::setLocale('zh');
         // Make a custom blade directive:
         Blade::directive('wrapParagraphs', function ($string) {
            $post = nl2br($string, false);
            $post = '<p>' . preg_replace('#(<br>[\r\n]+){2}#', '</p><p>', $post) . '</p>';
            return($post);
         });
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
