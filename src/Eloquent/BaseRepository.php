<?php

namespace AwesIO\Repository\Eloquent;

use AwesIO\Repository\Contracts\RepositoryInterface;
use AwesIO\Repository\Criteria\With;
use AwesIO\Repository\Criteria\FindWhere;

abstract class BaseRepository extends RepositoryAbstract implements RepositoryInterface
{    
    /**
     * Execute the query as a "select" statement.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get(array $columns = ['*'])
    {
        $results = $this->entity->get($columns);

        $this->reset();

        return $results;
    }

    /**
     * Execute the query and get the first result.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     */
    public function first($columns = ['*'])
    {
        $results = $this->entity->first($columns);

        $this->reset();

        return $results;
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*'])
    {
        $results = $this->entity->find($id, $columns);

        $this->reset();

        return $results;
    }

    /**
     * Add basic where clauses and execute the query.
     *
     * @param array $conditions
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findWhere(array $conditions, array $columns = ['*'])
    {
        return $this->withCriteria([
            new FindWhere($conditions)
        ])->get($columns);
    }

    /**
     * Paginate the given query.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*'])
    {
        $results = $this->entity->paginate($perPage, $columns);

        $this->reset();

        return $results;
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage = null, $columns = ['*'])
    {
        $results = $this->entity->simplePaginate($perPage, $columns);

        $this->reset();

        return $results;
    }

     /**
     * Save a new model and return the instance.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes)
    {
        $results = $this->entity->create($attributes);

        $this->reset();

        return $results;
    }

    /**
     * Update a record.
     *
     * @param  array  $values
     * @param  int  $id
     * 
     * @return int
     */
    public function update(array $values, $id, $attribute = "id")
    {
        $results = $this->entity->where($attribute, $id)->update($values);

        $this->reset();

        return $results;
    }

    /**
     * Delete a record by id.
     * 
     * @param  int  $id
     * 
     * @return mixed
     */
    public function destroy($id)
    {
        $results = $this->entity->destroy($id);

        $this->reset();

        return $results;
    }

    /**
     * Attach models to the parent.
     *
     * @param  int  $id
     * @param  string  $relation
     * @param  mixed   $ids
     * @return void
     */
    public function attach($id, $relation, $ids)
    {
        return $this->find($id)->{$relation}()->attach($ids);
    }

    /**
     * Detach models from the relationship.
     *
     * @param  int  $id
     * @param  string  $relation
     * @param  mixed   $ids
     * @return int
     */
    public function detach($id, $relation, $ids)
    {
        return $this->find($id)->{$relation}()->detach($ids);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static|static[]
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $results = $this->entity->findOrFail($id, $columns);

        $this->reset();

        return $results;
    }

    /**
     * Paginate the given query by 'limit' request parameter
     * @return mixed
     */
    public function smartPaginate()
    {
        $limit = request()->input(
            config('awesio-repository.smart_paginate.request_parameter'), 
            config('awesio-repository.smart_paginate.default_limit')
        );

        $maxLimit = config('awesio-repository.smart_paginate.max_limit');

        $limit = ($limit <= $maxLimit) ? $limit : $maxLimit;

        return $this->paginate($limit);
    }
}