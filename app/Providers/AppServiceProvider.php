<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use App\Models\BlogPost;
use App\Observers\BlogPostObserver;
use App\Models\Comment;
use App\Observers\CommentObserver;
use App\Services\Counter;
// use App\Services\ExampleCounter;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

      Schema::defaultStringLength(191);
      // Blade::componentNamespace('App\View\Components', 'tags');  // not working ... ???

      // view()->composer('*', ActivityComposer::class); // all
      view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);

      BlogPost::observe(BlogPostObserver::class);
      Comment::observe(CommentObserver::class);
      
      // $this->app->bind(Counter::class, function($app) {
      //   return new Counter(random_int(0, 100));
      // });

      $this->app->singleton(Counter::class, function($app) {
        return new Counter(
          $app->make('Illuminate\Contracts\Cache\Factory'),
          $app->make('Illuminate\Contracts\Session\Session'),
          env('COUNTER_TIMEOUT'));
      });

      $this->app->bind(
        // 'App\Contracts\CounterContract', ExampleCounter::class
        'App\Contracts\CounterContract', Counter::class  // interface, implementation
      );

      // CommentResource::withoutWrapping(); // remove 'data' key
      JsonResource::withoutWrapping();

      // if only primitive value (int, string, or arr) is needed:
      // $this->app->when(Counter::class)->needs('$timeout')->give(env('COUNTER_TIMEOUT'));

    }
}
