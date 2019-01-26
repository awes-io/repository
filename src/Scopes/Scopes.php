<?php

namespace AwesIO\Repository\Scopes;

class Scopes extends ScopesAbstract
{
    protected $scopes = [
        'orderBy' => OrderByScope::class,
    ];
}