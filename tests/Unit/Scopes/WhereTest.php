<?php

namespace AwesIO\Repository\Tests\Unit\Scopes;

use AwesIO\Repository\Tests\TestCase;
use AwesIO\Repository\Tests\Stubs\Model;
use AwesIO\Repository\Scopes\Clauses\WhereScope;

class WhereTest extends TestCase
{
    /** @test */
    public function it_scopes()
    {
        factory(Model::class, 5)->create();

        $model = factory(Model::class)->create();

        $scope = new WhereScope;

        $results = $scope->scope(new Model, $model->name, 'name')->get();

        $this->assertEquals($model->id, $results->first()->id);

        $this->assertCount(1, $results);
    }

    /** @test */
    public function it_scopes_by_where_clause()
    {
        factory(Model::class, 5)->create();

        $model1 = factory(Model::class)->create();
        $model2 = factory(Model::class)->create();

        $scope = new WhereScope;

        $builder = $scope->scope(new Model, $model1->name, 'name');

        $results = $scope->scope($builder, $model2->id, 'id')->get();

        $this->assertCount(0, $results);
    }
}