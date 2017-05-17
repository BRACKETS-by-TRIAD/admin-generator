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

        $modelName = class_basename($this->option('model')) ?: Str::studly(Str::singular($tableName));

        if ($this->alreadyExists($controllerPath)) {
            $this->error('File '.$controllerPath.' already exists!');
            return false;
        }

        $this->makeDirectory($controllerPath);

        $this->files->put($controllerPath, $this->buildClass($tableName, $controllerName, $controllerNamespace, $modelName));

        $this->info('Generating '.$controllerFullName.' finished');

    }

    protected function buildClass($tableName, $className, $namespace, $model) {

        return view('brackets/admin-generator::controller', [
            'className' => $className,
            'namespace' => $namespace,
            'modelName' => class_basename($model),
            // TODO maybe we should use Snake case as objectName - think about it
            'objectName' => $model ? lcfirst(Str::singular(class_basename($model))) : 'object',
            'modelFullName' => $model,
            'columns' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return !($column['name'] == "id" || $column['name'] == "created_at" || $column['name'] == "updated_at");
            })->map(function($column){
                $rules = collect([]);
                if ($column['required']) {
                    $rules->push('required');
                }

                if ($column['name'] == 'email') {
                    $rules->push('email');
                }

                switch ($column['type']) {
                    case 'datetime':
                    case 'date':
                    case 'time':
                        $rules->push('date');
                        break;
                    case 'integer':
                        $rules->push('integer');
                        break;
                    case 'boolean':
                        $rules->push('boolean');
                        break;
                    case 'float':
                    case 'decimal':
                        $rules->push('numeric');
                        break;
                    case 'string':
                    case 'text':
                    default:
                        $rules->push('string');
                }

                return [
                    'name' => $column['name'],
                    'rules' => $rules->toArray(),
                ];
            }),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Generates a controller for the given model'],
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
        ];
    }

}