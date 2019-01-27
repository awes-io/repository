<?php

namespace AwesIO\Repository\Scopes;

use AwesIO\Repository\Scopes\ScopeAbstract;

class WhereLikeScope extends ScopeAbstract
{
    public function scope($builder, $value, $scope)
    {
        return $builder->where($scope, 'like', "%$value%");
    }
}