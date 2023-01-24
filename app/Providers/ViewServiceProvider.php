<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View()->composer(['posts.index', 'posts.show'], 'App\Http\Composers\ActivityComposer');
    }
}
