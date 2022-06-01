<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:repository')]
class MakeRepository extends GeneratorCommand
{
    protected static $defaultName = 'make:repository';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:repository';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make Repository';
    protected $type = 'Repository';

    public function handle()
    {
        $response = parent::handle();
        if ($this->option('interface')) {
            $this->type = "Interface";
            $response = parent::handle();
            $file = file_get_contents(app_path('Providers/RepositoryBinding.php'));
            $repo = $this->qualifyClass($this->getNameRepository());
            $interface = $this->qualifyClass($this->getNameInterface());
            $text = "\$this->app->bind(\\$interface::class,\\$repo::class);";
            if (!str_contains($file, $text)) {
                $file .= "\n$text";
                file_put_contents(app_path('Providers/RepositoryBinding.php'), $file);
            }
        }
        return $response;
    }

    public function getNameRepository()
    {
        return \str($this->getNameInput())->replace('Interface', 'Repository');
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = Str::of(parent::getNameInput())->remove(['Repository', 'Interface'])->studly();
        return "$name{$this->type}";
    }

    public function getNameInterface()
    {
        return \str($this->getNameInput())->replace('Repository', 'Interface');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath($this->type === "Repository" ? '/stubs/repository.stub' : '/stubs/repositoryInterface.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $searches = [
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel', 'DummyRepositoryClass', 'DummyRepositoryInterface', 'DummyRepositoryModelClass', 'DummyName'],
            ['{{ namespace }}', '{{ rootNamespace }}', '{{ namespacedUserModel }}', '{{ RepositoryClass }}', '{{ RepositoryInterface }}', '{{ RepositoryModelClass }}', '{{ name }}'],
            ['{{namespace}}', '{{rootNamespace}}', '{{namespacedUserModel}}', '{{RepositoryClass}}', '{{RepositoryInterface}}', '{{RepositoryModelClass}}', '{{name}}'],
        ];
        $interface = $this->getNameInterface();
        $class = $this->getNameRepository();
        $baseName = str_replace(['Interface', 'Repository'], ['', ''], $class);
        $model = $this->qualifyModel($baseName);
        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$this->getNamespace($name), $this->rootNamespace(), $this->userProviderModel(), $class, $interface, $model, $baseName],
                $stub
            );
        }

        return $this;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $name = Str::of($this->getNameInput())->remove(['Repository', 'Interface'])->studly();

        return $rootNamespace . "\Repository\\" . $name;
    }

    /**
     * @return array[]
     */
    protected function getOptions()
    {
        return [
            ['interface', 'i', InputOption::VALUE_NEGATABLE, 'Generate a interface for the given repository.'],
        ];
    }
}
