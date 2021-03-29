<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class MyDateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mydate', function () {
            return new \App\MyApp\MyDate;
        });
    }
}
