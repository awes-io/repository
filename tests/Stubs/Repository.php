<?php

namespace AwesIO\Repository\Tests\Stubs;

use AwesIO\Repository\Eloquent\BaseRepository;

class Repository extends BaseRepository
{
    protected $searchable = [
        // 'field',
    ];

    public function entity()
    {
        return Model::class;
    }
}
