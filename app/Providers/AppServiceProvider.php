<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Blade::component('components.updated', 'updated');
        Blade::component('components.card', 'card-aside');
        Blade::component('components.tags', 'tags');
        Blade::component('components.error-list', 'errors');
        Blade::component('components.comment-form', 'comment-form');
        Blade::component('components.comments-list', 'comments-list');
    }
}
