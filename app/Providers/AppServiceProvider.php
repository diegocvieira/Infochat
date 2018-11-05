<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(!app()->environment('local')) {
            \URL::forceScheme('https');

            \Illuminate\Pagination\AbstractPaginator::currentPathResolver(function () {
                /** @var \Illuminate\Routing\UrlGenerator $url */
               $url = app('url');
               return $url->current();
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
