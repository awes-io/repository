<?php

namespace AwesIO\Repository\Criteria;

use AwesIO\Repository\Contracts\CriterionInterface;

class FindWhere implements CriterionInterface
{
    protected $conditions;
    
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }
    
    public function apply($entity)
    {
        foreach ($this->conditions as $field => $value) {

            if (is_array($value)) {

                list($field, $condition, $val) = $value;

                $entity = $entity->where($field, $condition, $val);

            } else {

                $entity = $entity->where($field, '=', $value);
            }
        }
        return $entity;
    }
}