<?php

namespace AwesIO\Repository\Tests\Unit;

use AwesIO\Repository\Tests\TestCase;
use Illuminate\Support\Facades\Request;
use AwesIO\Repository\Tests\Stubs\Model;
use AwesIO\Repository\Criteria\FindWhere;
use Illuminate\Database\Eloquent\Collection;
use AwesIO\Repository\Tests\Stubs\Repository;
use Illuminate\Database\Eloquent\Model as BaseModel;

class RepositoryTest extends TestCase
{
    /** @test */
    public function it_has_model_entity()
    {
        $repository = new Repository;

        $this->assertEquals(Model::class, $repository->entity());
    }

    /** @test */
    public function it_gets_collection()
    {
        $repository = new Repository;

        $this->assertInstanceOf(Collection::class, $repository->get());
    }

    /** @test */
    public function it_gets_first_model()
    {
        $model = factory(Model::class)->create();
        
        $repository = new Repository;

        $this->assertInstanceOf(BaseModel::class, $result = $repository->first());

        $this->assertEquals($model->name, $result->name);
    }

    /** @test */
    public function it_finds_model()
    {
        $model = factory(Model::class)->create();
        
        $repository = new Repository;

        $this->assertInstanceOf(BaseModel::class, $result = $repository->find($model->id));

        $this->assertEquals($model->name, $result->name);
    }

    /** @test */
    public function it_finds_model_by_where_clauses()
    {
        $model = factory(Model::class)->create();
        
        $repository = new Repository;

        $this->assertInstanceOf(Collection::class, $results = $repository->findWhere([
            'name' => $model->name
        ]));

        $this->assertEquals($model->id, $results->first()->id);
    }

    /** @test */
    public function it_resets_model()
    {
        $model1 = factory(Model::class)->create();

        $model2 = factory(Model::class)->create();
        
        $repository = new Repository;

        $results1 = $repository->findWhere([
            'name' => $model1->name
        ]);

        $results2 = $repository->findWhere([
            'name' => $model2->name
        ]);

        $this->assertEquals($model1->id, $results1->first()->id);

        $this->assertEquals($model2->id, $results2->first()->id);
    }

    /** @test */
    public function it_uses_criteria()
    {
        $model = factory(Model::class)->create();
        
        $repository = new Repository;

        $results = $repository->withCriteria([
            new FindWhere([
                'name' => $model->name
            ])
        ])->get();

        $this->assertEquals($model->id, $results->first()->id);
    }

    /** @test */
    public function it_scopes_request()
    {
        $model1 = factory(Model::class)->create();

        $model2 = factory(Model::class)->create();
        
        $repository = new Repository;

        $repository->searchable = [
            'name',
        ];

        $request = Request::create(
            '/',
            'GET',
            ['name' => $model2->name]
        );

        $results = $repository->scope($request)->get();

        $this->assertEquals($model2->id, $results->first()->id);
    }
}