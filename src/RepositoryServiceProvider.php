<?php

namespace AwesIO\Repository;

use AwesIO\Repository\Contracts\Repository as RepositoryContract;
use AwesIO\Repository\Repository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/awesio-repository.php' => config_path('awesio-repository.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/awesio-repository.php', 'awesio-repository');
    }
}
