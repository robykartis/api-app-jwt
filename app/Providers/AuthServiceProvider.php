<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Post;
use App\Models\User;
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
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('create-edit-delete-users', function (User $user) {
            if ($user->role_id === 1 || $user->role_id === 2) {
                return true;
            }
        });
        Gate::define('create-edit-delete-show-posts', function (User $user, Post $post) {
            if ($post->role_id === 3) {
                return true;
            }
        });
    }
}
