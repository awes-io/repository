<?php

namespace AwesIO\Repository\Tests\Unit;

use AwesIO\Repository\Tests\TestCase;
use AwesIO\Repository\Tests\Stubs\Model;
use AwesIO\Repository\Tests\Stubs\Repository;

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

        dd($repository->get());
    }
}