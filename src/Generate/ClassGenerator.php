<?php namespace Brackets\AdminGenerator\Generate;

use Brackets\AdminGenerator\Generate\Traits\Helpers;
use Brackets\AdminGenerator\Generate\Traits\Names;
use Brackets\AdminGenerator\Generate\Traits\Columns;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ClassGenerator extends Command {

    use Helpers, Columns, Names;

    public $classBaseName;
    public $classFullName;
    public $classNamespace;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * Relations
     *
     * @var string
     */
    protected $relations = [];

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    protected function getArguments() {
        return [
            ['table_name', InputArgument::REQUIRED, 'Name of the existing table'],
            ['class_name', InputArgument::OPTIONAL, 'Name of the generated class'],
        ];
    }

    /**
     * Generate default class name (for case if not passed as argument) from table name
     *
     * @param $tableName
     * @return mixed
     */
    abstract public function generateClassNameFromTable($tableName);

    /**
     * Build the class with the given name.
     *
     * @return string
     */
    abstract protected function buildClass();

    public function getPathFromClassName($name) {
        $path = str_replace('\\', '/', $name).".php";

        return preg_replace('|^App/|', 'app/', $path);
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    public function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    public function qualifyClass($name)
    {
        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    protected function generateClass($force = false) {
        $path = base_path($this->getPathFromClassName($this->classFullName));

        if ($this->alreadyExists($path)) {
            if($force) {
                $this->warn('File '.$path.' already exists! File will be deleted.');
                $this->files->delete($path);
            } else {
                $this->error('File '.$path.' already exists!');
                return false;
            }
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass());
        return true;
    }

    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this instanceof Model) {
            $this->initCommonNames($this->argument('table_name'), $this->argument('class_name'), null, $this->option('model-with-full-namespace'));
        } else {
            $this->initCommonNames($this->argument('table_name'), $this->option('model-name'), null, $this->option('model-with-full-namespace'));
        }

        $this->initClassNames($this->argument('class_name'));

        $output = parent::execute($input, $output);

        return $output;
    }

    protected function initClassNames($className = null) {
        if (empty($className)) {
            $className = $this->generateClassNameFromTable($this->tableName);
        }

        $this->classFullName = $this->qualifyClass($className);
        $this->classBaseName = class_basename($this->classFullName);
        $this->classNamespace = Str::replaceLast("\\".$this->classBaseName, '', $this->classFullName);
    }

}