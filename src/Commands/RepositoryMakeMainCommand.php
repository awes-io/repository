<?php

namespace AwesIO\Repository\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class RepositoryMakeMainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repository:generate {modelName} {--scope=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Awes.IO repository';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    protected $baseNamespace;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->baseNamespace = 'Repositories\\'.Str::plural($this->getNameInput());

        $this->createRepository();

        $scope = $this->option('scope');

        $this->createScopes(
            $scope 
                ? Str::singular($scope) . Str::plural($this->getNameInput()) . 'Scope' 
                : $scope,
            Str::camel($scope)
        );

        if ($scope) {
            $this->createScope($scope);
        }
    }

    /**
     * Create a repository.
     *
     * @return void
     */
    protected function createRepository()
    {
        $this->call('make:repository', [
            'name' => $this->getNamespacedRepository(),
            '--model' => $this->getNamespacedModel(),
            '--scopes' => $this->getNamespacedScopes(),
        ]);
    }

    /**
     * Create a repository scopes.
     *
     * @return void
     */
    protected function createScopes($scope, $scopeName)
    {
        $this->call('make:repository:scopes', [
            'name' => $this->getNamespacedScopes(),
            '--scope' => $scope,
            '--scope_name' => $scopeName,
        ]);
    }

    /**
     * Create a repository scope.
     *
     * @return void
     */
    protected function createScope($scope)
    {
        $this->call('make:repository:scope', [
            'name' => $this->getNamespacedScope($scope)
        ]);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return last(explode('/', trim($this->argument('modelName'))));
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    private function getNamespacedModel()
    {
        return implode(
            '\\', explode('/', trim($this->argument('modelName')))
        );
    }

    private function getNamespacedScopes()
    {
        return $this->baseNamespace .'\Scopes\\' 
            . Str::plural($this->getNameInput()) . 'Scopes';
    }

    private function getNamespacedScope($scope)
    {
        return $this->baseNamespace .'\Scopes\\' 
            . Str::singular($scope) . Str::plural($this->getNameInput()) . 'Scope';
    }

    private function getNamespacedRepository()
    {
        $repository = Str::studly(class_basename($this->getNameInput()));

        return $this->baseNamespace .'\\' 
            . Str::plural($repository) . 'Repository';
    }

}
