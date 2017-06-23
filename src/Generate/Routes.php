<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Routes extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append admin routes into a web routes file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $tableName = $this->argument('table_name');
        $controllerName = class_basename($this->option('controller')) ?: (Str::studly($tableName) . "Controller");
        $controllerFullName = "Admin\\".($this->option('controller') ?: (Str::studly($tableName) . "Controller"));
        $routesPath = base_path('routes/web.php');
        $controllerNamespace = Str::replaceLast("\\".$controllerName, '', $controllerFullName);

        $modelName = class_basename($this->option('model')) ?: Str::studly(Str::singular($tableName));

        $this->files->append($routesPath, "\n\n".$this->buildClass($controllerName, $controllerNamespace, $modelName));

        $this->info('Appending routes finished');

    }

    protected function buildClass($className, $namespace, $model) {

        return view('brackets/admin-generator::routes', [
            'className' => $className,
            'namespace' => $namespace,
            'objectName' => $objectName = ($model ? lcfirst(Str::singular(class_basename($model))) : 'object'),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Generates a controller for the given model'],
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
        ];
    }

}