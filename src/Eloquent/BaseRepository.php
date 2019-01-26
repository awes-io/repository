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
}