<?php

namespace AwesIO\Repository\Scopes;

class Scopes extends ScopesAbstract
{
    protected $scopes = [
        // 'orderBy' => OrderByScope::class,
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
            'like' => WhereLikeScope::class,
        ];

        return $mappings[$key] ?? WhereScope::class;
    }
}