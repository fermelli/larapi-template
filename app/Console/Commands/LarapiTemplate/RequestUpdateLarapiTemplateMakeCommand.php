<?php

namespace App\Console\Commands\LarapiTemplate;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:request-update-larapitemplate')]
class RequestUpdateLarapiTemplateMakeCommand extends GeneratorLarapiTemplateCommnad
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:request-update-larapitemplate';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:request-update-larapitemplate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request-update class for a Larapi Template';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = '/stubs/request.larapi-template.stub';

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
        $class = $this->getSingularCapitalizeWord($this->getResourceName($name)) . 'Actualizar' . $this->type;

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
        $nameSingular = $this->getSingularCapitalizeWord($name);

        return str_replace('\\', '/', $nameSingular) . "Actualizar$this->type.php";
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base request-update import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }
}
