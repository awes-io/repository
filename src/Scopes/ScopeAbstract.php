<?php

namespace AwesIO\Repository\Scopes;

abstract class ScopeAbstract
{
    abstract public function scope($builder, $value, $scope);

    public function mappings()
    {
        return [];
    }

    protected function resolveScopeValue($key)
    {
        return array_get($this->mappings(), $key);
    }
}