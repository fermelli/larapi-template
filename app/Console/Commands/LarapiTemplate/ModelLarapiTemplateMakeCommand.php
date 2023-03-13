<?php

namespace App\Console\Commands\LarapiTemplate;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:model-larapitemplate')]
class ModelLarapiTemplateMakeCommand extends GeneratorLarapiTemplateCommnad
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:model-larapitemplate';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:model-larapitemplate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model class for a Larapi Template';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('pivot')) {
            $stub = '/stubs/model.pivot.larapi-template.stub';
        } else {
            $stub = '/stubs/model.larapi-template.stub';
        }

        return $this->resolveStubPath($stub);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = $this->getResourceName($name);

        return str_replace(['{{ class }}', '{{class}}'], $class, $stub);
    }

    /**
     * Get file name.
     *
     * @param string $name
     * @return string
     */
    protected function getFileName($name)
    {
        return str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge([
            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
        ], parent::getOptions());
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base model import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        $resourceName = $this->getResourceName($name);
        $tableName = implode('_', array_map(function ($resource) {
            return Pluralizer::plural($resource);
        }, explode('_', Str::snake($resourceName))));

        $replace['{{ tableName }}'] = $tableName;
        $replace['{{tableName}}'] = $tableName;

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }
}
