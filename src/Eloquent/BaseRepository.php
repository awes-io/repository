<?php

namespace AwesIO\Repository\Eloquent;

use AwesIO\Repository\Contracts\RepositoryInterface;
use AwesIO\Repository\Criteria\With;

class BaseRepository extends RepositoryAbstract implements RepositoryInterface
{
    public function get()
    {
        return $this->entity->get();
    }
}