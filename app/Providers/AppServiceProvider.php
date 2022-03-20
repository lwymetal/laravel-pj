<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
      // Blade::componentNamespace('App\View\Components', 'tags');  // not working ... ???

      // view()->composer('*', ActivityComposer::class); // all
      view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);

    }
}
