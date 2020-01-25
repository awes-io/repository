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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use AwesIO\Repository\Contracts\RepositoryInterface;

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

    /** @test */
    public function it_finds_and_returns_respository_instance_or_fails()
    {
        $model = factory(Model::class, 20)->create();

        $repository = new Repository;

        $result = $repository->findOrFailRepo($model->first()->id);

        $this->assertInstanceOf(RepositoryInterface::class, $result);

        $this->expectException(ModelNotFoundException::class);

        $repository->findOrFailRepo(22);
    }

    /** @test */
    public function it_finds_first_or_fails()
    {
        $model = factory(Model::class)->create();
        
        $repository = new Repository;

        $result = $repository->where('id', $model->id)->firstOrFail();

        $this->assertInstanceOf(Model::class, $result);

        $this->assertEquals($model->name, $result->name);

        $this->expectException(ModelNotFoundException::class);

        $repository->where('id', $model->id + 1)->firstOrFail();
    }

    /** @test */
    public function it_can_smart_paginate()
    {
        $model = factory(Model::class, 20)->create();
        
        $repository = new Repository;

        $result = $repository->smartPaginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    /** @test */
    public function it_can_smart_paginate_by_default_limit()
    {
        $model = factory(Model::class, 20)->create();
        
        $repository = new Repository;

        $result = $repository->smartPaginate();

        $this->assertEquals(
            config('awesio-repository.smart_paginate.default_limit'), 
            $result->perPage()
        );
    }

    /** @test */
    public function it_can_smart_paginate_by_limit_parameter_if_its_less_or_equals_max_limit()
    {
        $model = factory(Model::class, 20)->create();
        
        $repository = new Repository;

        request()->merge([
            config('awesio-repository.smart_paginate.request_parameter') => $limit = random_int(
                1, config('awesio-repository.smart_paginate.max_limit')
            )
        ]);

        $result = $repository->smartPaginate();

        $this->assertEquals($limit, $result->perPage());
    }

    /** @test */
    public function it_can_smart_paginate_by_max_limit()
    {
        $model = factory(Model::class, 20)->create();
        
        $repository = new Repository;

        request()->merge([
            config('awesio-repository.smart_paginate.request_parameter') => $limit = random_int(
                $max = config('awesio-repository.smart_paginate.max_limit'), $max + 1000
            )
        ]);

        $result = $repository->smartPaginate();

        $this->assertEquals($max, $result->perPage());
    }

    /** @test */
    public function it_can_smart_paginate_if_request_parametert_is_string()
    {
        $model = factory(Model::class, 20)->create();
        
        $repository = new Repository;

        request()->merge([
            config('awesio-repository.smart_paginate.request_parameter') => uniqid()
        ]);

        $result = $repository->smartPaginate();

        $this->assertEquals(100, $result->perPage());
    }

    /** @test */
    public function it_executes_model_scopes()
    {
        $model = factory(Model::class)->create([
            'name' => $name = uniqid()
        ]);
        
        $repository = new Repository;

        $result = $repository->name($name)->get();

        $this->assertEquals($name, $result->first()->name);
    }

    /** @test */
    public function it_executes_model_methods()
    {
        $model = factory(Model::class)->create();
        
        $repository = new Repository;

        $result = $repository->submodels();

        $this->assertInstanceOf(Repository::class, $result);
    }

    /** @test */
    public function it_returns_model_props()
    {
        $model = factory(Model::class)->create();

        $model->submodels()->save(
            $submodel = factory(Submodel::class)->create()
        );
        
        $repository = new Repository;

        $result = $repository->findOrFailRepo($model->id)->submodels;

        $this->assertInstanceOf(Submodel::class, $result->first());

        $this->assertEquals($submodel->name, $result->first()->name);
    }

    /** @test */
    public function it_orders_by()
    {
        factory(Model::class, 5)->create();

        $repository = new Repository;

        $results = $repository->get();

        $this->assertEquals(1, $results->first()->id);

        $results = $repository->orderBy('id', 'desc')->get();

        $this->assertEquals(5, $results->first()->id);
    }
}