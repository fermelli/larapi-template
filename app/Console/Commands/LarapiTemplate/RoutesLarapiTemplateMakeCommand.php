<?php

namespace App\Console\Commands\LarapiTemplate;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:routes-larapitemplate')]
class RoutesLarapiTemplateMakeCommand extends GeneratorLarapiTemplateCommnad
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:routes-larapitemplate';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:routes-larapitemplate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new routes class for a Larapi Template';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'routes';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = '/stubs/routes.larapi-template.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel->basePath() . "\\api\\$name\\" . $this->getFileName($name);
    }

    /**
     * Get file name.
     *
     * @param string $name
     * @return string
     */
    protected function getFileName($name)
    {
        return "$this->type.php";
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base routes import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        $resourceName = $this->getResourceName($name);
        $resourceNameSingular = $this->getSingularCapitalizeWord($resourceName);
        $routeName = strtolower(Pluralizer::plural($resourceName));


        $replace['{{ resourceNameSingular }}'] = $resourceNameSingular;
        $replace['{{resourceNameSingular}}'] = $resourceNameSingular;
        $replace['{{ routeName }}'] = $routeName;
        $replace['{{routeName}}'] = $routeName;

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }
}
