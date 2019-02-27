<?php

namespace AwesIO\Repository\Tests\Unit;

use AwesIO\Repository\Tests\TestCase;
use Illuminate\Support\Facades\Request;
use AwesIO\Repository\Tests\Stubs\Model;
use AwesIO\Repository\Criteria\FindWhere;
use AwesIO\Repository\Tests\Stubs\Submodel;
use Illuminate\Database\Eloquent\Collection;
use AwesIO\Repository\Tests\Stubs\Repository;
use Illuminate\Contracts\Pagination\Paginator;
use AwesIO\Repository\Tests\Stubs\InvalidRepository;
use Illuminate\Database\Eloquent\Model as BaseModel;
use AwesIO\Repository\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    /** @test */
    public function it_throws_exception_if_entity_doesnt_belong_to_valid_class()
    {
        $this->expectException(RepositoryException::class);

        $repository = new InvalidRepository;
    }

    /** @test */
    public function it_paginates()
    {
        $model = factory(Model::class, 10)->create();
        
        $repository = new Repository;

        $this->assertInstanceOf(LengthAwarePaginator::class, $results = $repository->paginate());
    }

    /** @test */
    public function it_simple_paginates()
    {
        $model = factory(Model::class, 10)->create();
        
        $repository = new Repository;

        $this->assertInstanceOf(Paginator::class, $results = $repository->simplePaginate());
    }

    /** @test */
    public function it_creates_new_record()
    {
        $model = factory(Model::class)->make();
        
        $repository = new Repository;

        $results = $repository->create($model->getAttributes());

        $this->assertDatabaseHas('models', [
            'name' => $model->name
        ]);
    }

    /** @test */
    public function it_updates_existing_record()
    {
        $model = factory(Model::class)->create();
        
        $repository = new Repository;

        $results = $repository->update([
            'name' => $name = uniqid()
        ], $model->id);

        $this->assertDatabaseHas('models', [
            'name' => $name
        ]);
    }

    /** @test */
    public function it_destroys_existing_record_by_id()
    {
        $model = factory(Model::class)->create();

        $this->assertDatabaseHas('models', [
            'name' => $model->name
        ]);
        
        $repository = new Repository;

        $results = $repository->destroy($model->id);

        $this->assertDatabaseMissing('models', [
            'name' => $model->name
        ]);
    }

    /** @test */
    public function it_attaches_model_to_parent()
    {
        $model = factory(Model::class)->create();

        $submodel = factory(Submodel::class)->create();
        
        $repository = new Repository;

        $repository->attach($model->id, 'submodels', $submodel->id);

        $this->assertDatabaseHas('model_submodel', [
            'model_id' => $model->id,
            'submodel_id' => $submodel->id
        ]);
    }

    /** @test */
    public function it_detaches_model_from_parent()
    {
        $model = factory(Model::class)->create();

        $submodel = factory(Submodel::class)->create();
        
        $repository = new Repository;

        $repository->attach($model->id, 'submodels', $submodel->id);

        $this->assertDatabaseHas('model_submodel', [
            'model_id' => $model->id,
            'submodel_id' => $submodel->id
        ]);

        $repository->detach($model->id, 'submodels', $submodel->id);

        $this->assertDatabaseMissing('model_submodel', [
            'model_id' => $model->id,
            'submodel_id' => $submodel->id
        ]);
    }

    /** @test */
    public function it_finds_or_fails()
    {
        $model = factory(Model::class)->create();
        
        $repository = new Repository;

        $result = $repository->findOrFail($model->id);

        $this->assertEquals($model->name, $result->name);

        $this->expectException(ModelNotFoundException::class);

        $repository->findOrFail($model->id + 1);
    }
}