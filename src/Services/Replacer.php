<?php

namespace AwesIO\Repository\Services;

use Illuminate\Support\Str;

class Replacer
{
    protected $stub;

    public function __construct(string $stub)
    {
        $this->stub = $stub;
    }

    public function replace(...$replaceables)
    {
        foreach ($replaceables as $replaceable) {

            $exploded = explode('\\', $replaceable);

            end($exploded);

            $resource = prev($exploded);

            $this->replaceDummies($replaceable, Str::singular($resource));
        }
        return $this->stub;
    }

    private function replaceDummies($model, $name)
    {
        $model = str_replace('/', '\\', $model);

        $this->replaceNamespacedDummies($model, $name);
        
        $model = class_basename(trim($model, '\\'));

        $this->replaceNonNamespacedDummies($model, $name);
    }

    private function replaceNamespacedDummies($model, $name)
    {
        $namespaceModel = app()->getNamespace() . $model;

        $stub = (Str::startsWith($model, '\\')) 
            ? str_replace('NamespacedDummy' . $name, trim($model, '\\'), $this->stub)
            : str_replace('NamespacedDummy' . $name, $namespaceModel, $this->stub);

        $this->stub = str_replace(
            "use {$namespaceModel};\nuse {$namespaceModel};", "use {$namespaceModel};", $stub
        );
    }

    private function replaceNonNamespacedDummies($model, $name)
    {
        $stub = str_replace('DocDummy' . $name, Str::snake($model, ' '), $this->stub);

        $stub = str_replace('Dummy' . $name, $model, $stub);

        $plural = Str::plural(Str::before($model, $name));

        $stub = str_replace('dummy' . $name, Str::camel($plural), $stub);

        $this->stub = str_replace('dummySnake' . $name, Str::snake($plural), $stub);
    }
}
