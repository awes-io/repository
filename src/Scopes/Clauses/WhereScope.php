<?php

namespace AwesIO\Repository\Scopes\Clauses;

use AwesIO\Repository\Scopes\ScopeAbstract;

class WhereScope extends ScopeAbstract
{
    public function scope($builder, $value, $scope)
    {
        return $builder->where($scope, $value);
    }
}