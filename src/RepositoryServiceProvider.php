<?php

namespace AwesIO\Repository;

use Illuminate\Support\ServiceProvider;
use AwesIO\Repository\Commands\RepositoryMakeCommand;
use AwesIO\Repository\Commands\RepositoryMakeMainCommand;
use AwesIO\Repository\Commands\RepositoryScopeMakeCommand;
use AwesIO\Repository\Commands\RepositoryScopesMakeCommand;

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

        if ($this->app->runningInConsole()) {
            $this->commands([
                RepositoryMakeMainCommand::class,
                RepositoryMakeCommand::class,
                RepositoryScopesMakeCommand::class,
                RepositoryScopeMakeCommand::class,
            ]);
        }
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
