<?php

namespace AwesIO\Repository\Scopes\Clauses;

use AwesIO\Repository\Scopes\ScopeAbstract;

class OrderByScope extends ScopeAbstract
{
    public function scope($builder, $value, $scope)
    {
        $arr = explode('_', $value);

        $orderable = $builder->orderable ?? [];

        if (array_pop($arr) == 'desc' 
            && in_array($field = implode('_', $arr), $orderable)) {

            return $builder->orderBy($field, 'desc');

        } elseif (in_array($value, $orderable)) {

            return $builder->orderBy($value, 'asc');
        }
        return $builder;
    }
}