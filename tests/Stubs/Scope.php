<?php

namespace AwesIO\Repository\Tests\Stubs;

use AwesIO\Repository\Scopes\ScopeAbstract;

class Scope extends ScopeAbstract
{
    public function mappings()
    {
        return ['full_name' => 'name'];
    }

    public function scope($builder, $value, $scope)
    {
        $scope = $this->resolveScopeValue($scope);
        
        return $builder->where($scope, $value);
    }
}