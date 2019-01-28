<?php

namespace AwesIO\Repository\Tests\Unit\Scopes;

use AwesIO\Repository\Scopes\Scopes;
use AwesIO\Repository\Tests\TestCase;
use Illuminate\Support\Facades\Request;
use AwesIO\Repository\Tests\Stubs\Model;

class ScopesTest extends TestCase
{
    /** @test */
    public function it_scopes_request_by_orderBy()
    {
        $request = Request::create(
            '/',
            'GET',
            ['orderBy' => 'id']
        );

        $scopes = new Scopes($request, []);

        factory(Model::class, 5)->create();

        $results = Model::get();

        $this->assertEquals(1, $results->first()->id);

        $results = $scopes->scope(new Model)->get();

        $this->assertEquals(5, $results->first()->id);
    }

    /** @test */
    public function it_scopes_request_by_begin_date()
    {
        $model = factory(Model::class)->create();

        $date = $model->created_at;

        $request = Request::create(
            '/',
            'GET',
            ['begin' => $date->subYear()->toDateString()]
        );

        $scopes = new Scopes($request, []);

        $results = $scopes->scope(new Model)->get();

        $this->assertEquals(1, $results->count());

        $date = $model->created_at;

        $request = Request::create(
            '/',
            'GET',
            ['begin' => $date->addYear()->toDateString()]
        );

        $scopes = new Scopes($request, []);

        $results = $scopes->scope(new Model)->get();

        $this->assertEquals(0, $results->count());
    }

    /** @test */
    public function it_scopes_request_by_end_date()
    {
        $model = factory(Model::class)->create();

        $date = $model->created_at;

        $request = Request::create(
            '/',
            'GET',
            ['end' => $date->subYear()->toDateString()]
        );

        $scopes = new Scopes($request, []);

        $results = $scopes->scope(new Model)->get();

        $this->assertEquals(0, $results->count());

        $date = $model->created_at;
        
        $request = Request::create(
            '/',
            'GET',
            ['end' => $date->addYear()->toDateString()]
        );

        $scopes = new Scopes($request, []);

        $results = $scopes->scope(new Model)->get();

        $this->assertEquals(1, $results->count());
    }
}