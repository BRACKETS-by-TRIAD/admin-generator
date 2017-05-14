<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Model extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a model class';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $tableName = $this->argument('table_name');
        $modelName = class_basename($this->option('model')) ?: Str::studly(Str::singular($tableName));
        $modelFullName = $this->qualifyClass("Models\\".($this->option('model') ?: Str::studly(Str::singular($tableName))));
        $modelPath = $this->getPath($modelFullName);
        $modelNamespace = Str::replaceLast("\\".$modelName, '', $modelFullName);

        print_r($this->readColumnsFromTable($tableName));return ;


        if ($this->alreadyExists($modelPath)) {
            $this->error('File '.$modelPath.' already exists!');
            return false;
        }

        $this->makeDirectory($modelPath);

        $this->files->put($modelPath, $this->buildClass($tableName, $modelName, $modelNamespace));

        // TODO think if we should use ide-helper:models ?

        $this->info('Generating '.$modelFullName.' finished');

    }

    protected function buildClass($tableName, $class, $namespace) {

        return view('brackets/admin-generator::model', [
            'className' => $class,
            'namespace' => $namespace,
            'dates' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return $column['type'] == "datetime" || $column['type'] == "date";
            })->pluck('name'),
            'fillable' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return !in_array($column['name'], ['id', 'created_at', 'updated_at']);
            })->pluck('name'),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
        ];
    }

}