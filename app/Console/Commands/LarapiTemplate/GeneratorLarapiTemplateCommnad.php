<?php

namespace App\Console\Commands\LarapiTemplate;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class GeneratorLarapiTemplateCommnad extends GeneratorCommand
{
    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $nameInput = rtrim($this->getNameInput(), '/\\');


        if ($this->isReservedName($nameInput)) {
            $this->components->error('The name "' . $nameInput . '" is reserved by PHP.');

            return false;
        }

        $nameInputArray = preg_split("#[\\\\/]+#", $nameInput);

        $wordCount = count($nameInputArray);

        if ($wordCount > 2) {
            $this->components->error('The name "' . $nameInput . '" is invalid. Please use the format "Directory\\ClassName" or "ClassName".');

            return false;
        }

        if ($wordCount == 1) {
            $nameInput = $nameInputArray[0] . '\\' . Pluralizer::singular($nameInputArray[0]);
        }

        $name = $this->qualifyClass($nameInput);

        $path = $this->getPath($name);

        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($nameInput)
        ) {
            $this->components->error($this->type . ' already exists.');

            if ($this->type == 'routes') {
                $this->components->info('Add the route manually to the file "' . $path . '"');
            }

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $info = $this->type;

        $this->components->info(sprintf('%s [%s] created successfully.', $info, $path));
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

        $names = preg_split("#[\\\\/]+#", $name);

        $rootDirectory = $names[0];

        $name = $names[1];

        $directory = $this->getPluralCapitalize($this->type);

        $path = $this->laravel->basePath() . "\\api\\$rootDirectory\\$directory\\" . $this->getFileName($name);

        return $path;
    }

    /**
     * Get file name.
     *
     * @param string $name
     * @return string
     */
    protected function getFileName($name)
    {
        return str_replace('\\', '/', $name) . $this->getSingularCapitalize($this->type) . '.php';
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stubdd
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = $this->getResourceName($name) . $this->getSingularCapitalize($this->type);

        return str_replace(['{{ class }}', '{{class}}'], $class, $stub);
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Get the resource name.
     *
     * @return string
     */
    protected function getResourceName($name)
    {
        return trim(str_replace($this->getNamespace($name), '', $name), '\\');
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'Api\\';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
        ];
    }

    /**
     * Return the Singular Capitalize Word
     *
     * @param $word
     * @return string
     */
    public function getSingularCapitalize($word)
    {
        return ucfirst(Pluralizer::singular($word));
    }

    /**
     * Return the Plural Capitalize Word
     *
     * @param $word
     * @return string
     */
    public function getPluralCapitalize($word)
    {
        return ucfirst(Pluralizer::plural($word));
    }
}
