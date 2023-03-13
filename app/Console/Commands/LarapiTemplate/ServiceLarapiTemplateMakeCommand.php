<?php

namespace App\Console\Commands\LarapiTemplate;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:service-larapitemplate')]
class ServiceLarapiTemplateMakeCommand extends GeneratorLarapiTemplateCommnad
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service-larapitemplate';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:service-larapitemplate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class for a Larapi Template';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = '/stubs/service.larapi-template.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge([
            ['repository', 'r', InputOption::VALUE_NONE, 'Indicates if also a repository should be created'],
            ['not-found-exception', 'e', InputOption::VALUE_NONE, 'Indicates if also a not found exception should be created'],
            ['model', 'm', InputOption::VALUE_NONE, 'Indicates if also a model should be created'],
            ['all', 'a', InputOption::VALUE_NONE, 'Indicates if also a repository, model and not found exception should be created'],
        ], parent::getOptions());
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base service import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        $resourceName = $this->getResourceName($name);
        $partialVariableName = lcfirst($resourceName);

        $replace['{{ resourceName }}'] = $resourceName;
        $replace['{{resourceName}}'] = $resourceName;
        $replace['{{ partialVariableName }}'] = $partialVariableName;
        $replace['{{partialVariableName}}'] = $partialVariableName;

        $arguments = [
            'name' => $this->getNameInput(),
        ];

        if (
            $this->hasOption('repository') && $this->option('repository')
            || $this->hasOption('all') && $this->option('all')
        ) {
            $this->call('make:repository-larapitemplate', $arguments);
        }

        if (
            $this->hasOption('not-found-exception') && $this->option('not-found-exception')
            || $this->hasOption('all') && $this->option('all')
        ) {
            $this->call('make:exception-not-found-larapitemplate', $arguments);
        }

        if (
            $this->hasOption('model') && $this->option('model')
            || $this->hasOption('all') && $this->option('all')
        ) {
            $this->call('make:model-larapitemplate', $arguments);
        }


        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }
}
