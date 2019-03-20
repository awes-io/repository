<?php

namespace AwesIO\Repository\Contracts;

interface RepositoryInterface
{
    /**
     * Execute the query as a "select" statement.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get(array $columns = ['*']);

    /**
     * Execute the query and get the first result.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     */
    public function first($columns = ['*']);

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']);

     /**
     * Add basic where clauses and execute the query.
     *
     * @param array $conditions
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findWhere(array $conditions, array $columns = ['*']);

    /**
     * Paginate the given query.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*']);

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage = null, $columns = ['*']);

     /**
     * Save a new model and return the instance.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes);

    /**
     * Update a record.
     *
     * @param  array  $values
     * @param  int  $id
     * 
     * @return int
     */
    public function update(array $values, $id, $attribute = "id");

    /**
     * Delete a record by id.
     * 
     * @param  int  $id
     * 
     * @return mixed
     */
    public function destroy($id);

    /**
     * Attach models to the parent.
     *
     * @param  int  $id
     * @param  string  $relation
     * @param  mixed   $ids
     * @return void
     */
    public function attach($id, $relation, $ids);

    /**
     * Detach models from the relationship.
     *
     * @param  int  $id
     * @param  string  $relation
     * @param  mixed   $ids
     * @return int
     */
    public function detach($id, $relation, $ids);

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static|static[]
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*']);

    /**
     * Paginate the given query by 'limit' request parameter
     * @return mixed
     */
    public function smartPaginate();

    /**
     * Add an "order by" clause to the query.
     *
     * @param  string  $column
     * @param  string  $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc');
}