<?php

namespace AwesIO\Repository\Tests;

use Illuminate\Database\Schema\Blueprint;
use AwesIO\Repository\RepositoryServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        // $this->artisan('db:seed', ['--class' => 'AwesIO\News\Seeds\NewsCategoriesTableSeeder']);

        // $this->withFactories(__DIR__ . '/../database/factories');
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
        // $builder = $app['db']->connection()->getSchemaBuilder();

        // $builder->create('news_categories', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('name');
        //     $table->string('meta_title', 100);
        //     $table->string('meta_description', 200);
        //     $table->string('slug')->unique();
        // });

        // $builder->create('news', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('news_category_id')->unsigned();
        //     $table->string('title');
        //     $table->text('body');
        //     $table->string('slug')->unique();
        //     $table->string('meta_title', 100);
        //     $table->string('meta_description', 200);
        //     $table->string('cover', 500);
        //     $table->timestamps();

        //     $table->foreign('news_category_id')->references('id')->on('news_categories')->onDelete('cascade');
        // });
    }
}