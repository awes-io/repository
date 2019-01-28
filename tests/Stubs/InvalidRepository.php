<?php

namespace AwesIO\Repository\Tests\Stubs;

use AwesIO\Repository\Eloquent\BaseRepository;
use AwesIO\Repository\Scopes\Clauses\WhereScope;

class InvalidRepository extends BaseRepository
{
    public $searchable = [];

    public function entity()
    {
        return WhereScope::class;
    }
}
