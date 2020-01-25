<?php

namespace AwesIO\Repository\Tests\Unit\Scopes;

use AwesIO\Repository\Tests\TestCase;
use AwesIO\Repository\Tests\Stubs\Model;
use AwesIO\Repository\Scopes\Clauses\OrWhereLikeScope;
use Illuminate\Support\Str;

class OrWhereLikeTest extends TestCase
{
    /** @test */
    public function it_scopes()
    {
        factory(Model::class, 5)->create();

        $model = factory(Model::class)->create();

        $scope = new OrWhereLikeScope;

        $results = $scope->scope(
            new Model, 
            Str::after($model->name, $model->name[0]),
            'name'
        )->get();

        $this->assertEquals($model->id, $results->first()->id);

        $this->assertCount(1, $results);
    }

    /** @test */
    public function it_scopes_by_orwhere_clause()
    {
        factory(Model::class, 5)->create();

        $model1 = factory(Model::class)->create();
        $model2 = factory(Model::class)->create();

        $scope = new OrWhereLikeScope;

        $builder = $scope->scope(
            new Model, 
            Str::after($model1->name, $model1->name[0]),
            'name'
        );

        $results = $scope->scope(
            $builder, 
            Str::after($model2->name, $model2->name[0]),
            'name'
        )->get();

        $this->assertCount(2, $results);
    }
}