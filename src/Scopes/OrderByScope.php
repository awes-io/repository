<?php

namespace AwesIO\Repository\Scopes;

class OrderByScope extends ScopeAbstract
{
    public function mappings()
    {
        return [
            'id' => 'desc',
            'title' => 'desc',
        ];
    }

    public function scope($builder, $value)
    {
        $field = $value;

        $value = $this->resolveScopeValue($value);

        return is_null($value) 
            ? $builder 
            : $builder->orderBy($field, $value);
    }
}