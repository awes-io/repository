<?php

namespace AwesIO\Repository\Tests\Unit\Scopes;

use AwesIO\Repository\Tests\TestCase;
use AwesIO\Repository\Tests\Stubs\Model;
use AwesIO\Repository\Scopes\Clauses\OrderByScope;

class OrderByTest extends TestCase
{
    /** @test */
    public function it_orders_by_asc()
    {
        factory(Model::class, 5)->create();

        $scope = new OrderByScope;

        $results = Model::get();

        $this->assertEquals(1, $results->first()->id);

        $results = $scope->scope(new Model, 'id', '')->get();

        $this->assertEquals(1, $results->first()->id);
    }

    /** @test */
    public function it_orders_by_desc()
    {
        factory(Model::class, 5)->create();

        $scope = new OrderByScope;

        $results = Model::get();

        $this->assertEquals(1, $results->first()->id);

        $results = $scope->scope(new Model, 'id_desc', '')->get();

        $this->assertEquals(5, $results->first()->id);
    }
}