<?php

namespace AwesIO\Repository\Contracts;

interface CriterionInterface
{
    public function apply($entity);
}