<?php

namespace AwesIO\Repository\Scopes;

class WhereScope extends ScopeAbstract
{
    public function scope($builder, $value, $scope)
    {
        return $builder->where($scope, $value);
    }
}