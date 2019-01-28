<?php

namespace AwesIO\Repository\Scopes;

use AwesIO\Repository\Scopes\Clauses\WhereScope;
use AwesIO\Repository\Scopes\Clauses\OrderByScope;
use AwesIO\Repository\Scopes\Clauses\OrWhereScope;
use AwesIO\Repository\Scopes\Clauses\WhereLikeScope;
use AwesIO\Repository\Scopes\Clauses\OrWhereLikeScope;
use AwesIO\Repository\Scopes\Clauses\WhereDateLessScope;
use AwesIO\Repository\Scopes\Clauses\WhereDateGreaterScope;

class Scopes extends ScopesAbstract
{
    protected $scopes = [
        'orderBy' => OrderByScope::class,
        'begin' => WhereDateGreaterScope::class,
        'end' => WhereDateLessScope::class,
    ];

    public function __construct($request, $searchable)
    {
        parent::__construct($request);

        foreach ($searchable as $key => $value) {

            if (is_string($key)) {
                $this->scopes[$key] = $this->mappings($value);
            } else {
                $this->scopes[$value] = WhereScope::class;
            }
        }
    }

    protected function mappings($key)
    {
        $mappings = [
            'or' => OrWhereScope::class,
            'like' => WhereLikeScope::class,
            'orLike' => OrWhereLikeScope::class,
        ];

        return $mappings[$key] ?? WhereScope::class;
    }
}