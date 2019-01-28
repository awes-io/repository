<?php

namespace AwesIO\Repository\Scopes\Clauses;

use AwesIO\Repository\Scopes\ScopeAbstract;

class OrWhereScope extends ScopeAbstract
{
    public function scope($builder, $value, $scope)
    {
        return $builder->orWhere($scope, $value);
    }
}