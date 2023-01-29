<?php

namespace App\Providers;

use App\Models\Author;
use App\Policies\AuthorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Blogpost' => 'App\Policies\BlogpostPolicy',
        Author::class => AuthorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        Gate::define('home.contact.secret', fn(Author $user) => $user->role->name == 'admin');

        Gate::before(function(Author $user, $ability) {
            if($user->role->name == 'admin') {
                return true;
            }
        });
    }
}
