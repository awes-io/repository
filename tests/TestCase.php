<?php

namespace AwesIO\Repository\Tests;

use Illuminate\Database\Schema\Blueprint;
use AwesIO\Repository\RepositoryServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // $this->artisan('db:seed', ['--class' => 'AwesIO\News\Seeds\NewsCategoriesTableSeeder']);

        $this->withFactories(__DIR__ . '/../database/factories');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', env('APP_DEBUG', true));

        $this->setUpDatabase($app);
    }

    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            RepositoryServiceProvider::class
        ];
    }

    protected function setUpDatabase($app)
    {
        $builder = $app['db']->connection()->getSchemaBuilder();

        $builder->create('models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $builder->create('submodels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $builder->create('model_submodel', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_id');
            $table->integer('submodel_id');
        });
    }
}