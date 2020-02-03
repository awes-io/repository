<?php

namespace AwesIO\Repository\Commands;

use AwesIO\Repository\Services\Replacer;
use Illuminate\Console\GeneratorCommand;

class RepositoryScopesMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository:scopes {name} {--scope=} {--scope_name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Awes.IO repository scopes';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Awes.IO platform repository scopes';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false) {
            return true;
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = (new Replacer(parent::buildClass($name)))
            ->replace($name);

        if ($scope = $this->option('scope')) {
            $stub = str_replace('SearchScope', $scope, $stub);
            $scopeName = $this->option('scope_name');
            $stub = str_replace('search', $scopeName, $stub);
        }

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/scopes.stub';
    }
}
