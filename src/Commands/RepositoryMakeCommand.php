<?php

namespace AwesIO\Repository\Commands;

use AwesIO\Repository\Services\Replacer;
use Illuminate\Console\GeneratorCommand;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name} {--model=} {--scopes=}';

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
    protected $type = 'Awes.IO platform repository';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return false;
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
        $model = $this->option('model');

        $scopes = $this->option('scopes');

        $stub = (new Replacer(parent::buildClass($name)))
            ->replace($scopes);

        $stub = str_replace('NamespacedDummyModel', $model, $stub);

        return str_replace('DummyModel', last(explode('\\', $model)), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/repository.stub';
    }
}
