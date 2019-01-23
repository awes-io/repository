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
        // $this->publishes([
        //     __DIR__.'/../config/repository.php' => config_path('repository.php'),
        // ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(RepositoryContract::class, Repository::class);
        // $this->app->singleton('repository', RepositoryContract::class);
    }
}
