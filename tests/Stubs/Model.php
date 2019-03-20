<?php

namespace AwesIO\Repository\Tests\Stubs;

use AwesIO\Repository\Tests\Stubs\Submodel;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public $orderable = ['id'];

    protected $fillable = ['name'];

    public function submodels()
    {
        return $this->belongsToMany(Submodel::class);
    
    }
    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }
}
