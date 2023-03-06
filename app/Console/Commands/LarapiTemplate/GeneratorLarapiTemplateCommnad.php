<?php

namespace App\Console\Commands\LarapiTemplate;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

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
        if (str_word_count($this->getNameInput()) > 1) {
            $this->components->error('The name of the resource cannot contain more than one word');

            return false;
        }

        if (strpos($this->getNameInput(), '/') !== false) {
            $this->components->error('The name of the resource cannot contain the "/" character');

            return false;
        }

        parent::handle();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the resource'],
        ];
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return $this->getPluralCapitalizeWord(trim($this->argument('name')));
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

        $directory = $this->getPluralCapitalizeWord($this->type);

        return $this->laravel->basePath() . "\\api\\$name\\$directory\\" . $this->getFileName($name);
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

        return str_replace('\\', '/', $nameSingular) . "$this->type.php";
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
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = $this->getSingularCapitalizeWord($this->getResourceName($name)) . $this->type;

        return str_replace(['{{ class }}', '{{class}}'], $class, $stub);
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        return $name;
    }

    /**
     * Get the resource name.
     *
     * @return string
     */
    protected function getResourceName($name)
    {
        return str_replace($this->rootNamespace(), '', $this->getNamespace($name));
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
     * Return the Singular Capitalize Word
     *
     * @param $word
     * @return string
     */
    public function getSingularCapitalizeWord($word)
    {
        return ucfirst(Pluralizer::singular($word));
    }

    /**
     * Return the Plural Capitalize Word
     *
     * @param $word
     * @return string
     */
    public function getPluralCapitalizeWord($word)
    {
        return ucfirst(Pluralizer::plural($word));
    }
}
