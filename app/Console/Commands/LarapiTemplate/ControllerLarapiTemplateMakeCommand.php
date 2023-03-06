<?php

namespace App\Console\Commands\LarapiTemplate;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:controller-larapitemplate')]
class ControllerLarapiTemplateMakeCommand extends GeneratorLarapiTemplateCommnad
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:controller-larapitemplate';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:controller-larapitemplate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class for a Larapi Template';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = '/stubs/controller.larapi-template.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
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
        $variableSnakeCase = Str::snake($resourceNameSingular);

        $replace['{{ resourceNameSingular }}'] = $resourceNameSingular;
        $replace['{{resourceNameSingular}}'] = $resourceNameSingular;
        $replace['{{ partialVariableName }}'] = $partialVariableName;
        $replace['{{partialVariableName}}'] = $partialVariableName;
        $replace['{{ variableSnakeCase }}'] = $variableSnakeCase;
        $replace['{{variableSnakeCase}}'] = $variableSnakeCase;

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }
}
