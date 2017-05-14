<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Controller extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a controller class';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $tableName = $this->argument('table_name');
        $controllerName = class_basename($this->option('controller')) ?: (Str::studly($tableName) . "Controller");
        $controllerFullName = $this->qualifyClass("Http\\Controllers\\Admin\\".($this->option('controller') ?: (Str::studly($tableName) . "Controller")));
        $controllerPath = $this->getPath($controllerFullName);
        $controllerNamespace = Str::replaceLast("\\".$controllerName, '', $controllerFullName);

        if ($this->alreadyExists($controllerPath)) {
            $this->error('File '.$controllerPath.' already exists!');
            return false;
        }

        $this->makeDirectory($controllerPath);

        $this->files->put($controllerPath, $this->buildClass($tableName, $controllerName, $controllerNamespace, $this->option('model')));

        $this->info('Generating '.$controllerFullName.' finished');

    }

    protected function buildClass($tableName, $className, $namespace, $model) {

        return view('brackets/admin-generator::controller', [
            'className' => $className,
            'namespace' => $namespace,
            'modelName' => class_basename($model),
            'modelFullName' => $model,
            'TODO' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return $column['type'] == "datetime" || $column['type'] == "date";
            })->pluck('name'),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generates a controller for the given model'],
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
        ];
    }

}