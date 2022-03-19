<?php

namespace App\Providers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      'App\Models\Model' => 'App\Policies\ModelPolicy',
      'App\Models\BlogPost' => 'App\Policies\BlogPostPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies(); // makes it go, don't remove

        Gate::define('home.secret', function ($user) {
          return $user->is_admin;
        });

        // Gate::define('update-post', function($user, $post) {
        //   if ($user->id !== $post->user_id) {
        //     throw new AuthorizationException("You shall not pass! [edit]");
        //   }
        //   return true;
        // });

        // Gate::define('delete-post', function($user, $post) {
        //   if ($user->id !== $post->user_id) {
        //     throw new AuthorizationException("You shall not pass! [delete]");
        //   }
        //   return true;
        // });

        // Gate::define('posts.update', 'App\Policies\BlogPostPolicy@update');
        // Gate::define('posts.delete', 'App\Policies\BlogPostPolicy@delete');

        // Gate::resource('posts', 'App\Policies\BlogPostPolicy');
        // gives all resource actions: posts.create, posts.view, posts.update, posts.delete

        Gate::before(function ($user, $ability) { // always called first regardless of order
          if ($user->is_admin && in_array($ability, ['update', 'delete'])) {
            return true;
          };
        });

        // Gate::after(function ($user, $ability, $result) {
        //   if ($user->is_admin) {
        //     return true;
        //   };
        // });
    }
}
