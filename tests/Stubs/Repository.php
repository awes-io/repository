<?php

namespace AwesIO\Repository\Tests\Stubs;

use AwesIO\Repository\Eloquent\BaseRepository;

class Repository extends BaseRepository
{
    public $searchable = [];

    public function entity()
    {
        return Model::class;
    }
}
