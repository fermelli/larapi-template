<?php

namespace App\Console\Commands\LarapiTemplate;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:repository-larapitemplate')]
class RepositoryLarapiTemplateMakeCommand extends GeneratorLarapiTemplateCommnad
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repository-larapitemplate';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:repository-larapitemplate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class for a Larapi Template';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = '/stubs/repository.larapi-template.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base repository import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        $resourceName = $this->getResourceName($name);
        $resourceNameSingular = $this->getSingularCapitalizeWord($resourceName);
        $partialVariableName = strtolower($resourceNameSingular);

        $replace['{{ resourceNameSingular }}'] = $resourceNameSingular;
        $replace['{{resourceNameSingular}}'] = $resourceNameSingular;
        $replace['{{ partialVariableName }}'] = $partialVariableName;
        $replace['{{partialVariableName}}'] = $partialVariableName;

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }
}
