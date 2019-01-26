<?php

namespace AwesIO\Repository\Scopes;

class ScopesAbstract
{
    protected $request;

    protected $scopes = [];

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function scope($builder)
    {
        $scopes = $this->getScopes();

        foreach ($scopes as $scope => $value) {
            $builder = $this->resolveScope($scope)->scope($builder, $value, $scope);
        }
        return $builder;
    }

    protected function resolveScope($scope)
    {
        return new $this->scopes[$scope];
    }
    
    protected function getScopes()
    {
        return $this->filterScopes(
            $this->request->only(array_keys($this->scopes))
        );
    }

    protected function filterScopes($scopes)
    {
        return array_filter($scopes, function ($scope) {
                return isset($scope);
            }
        );
    }
}