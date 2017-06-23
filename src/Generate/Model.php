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
        $modelPath = base_path($this->getPathFromClassName($this->modelFullName));

        if ($this->alreadyExists($modelPath)) {
            $this->error('File '.$modelPath.' already exists!');
            return false;
        }

        $this->makeDirectory($modelPath);

        $this->files->put($modelPath, $this->buildClass());

        // TODO think if we should use ide-helper:models ?

        $this->info('Generating '.$this->modelBaseName.' finished');

    }

    protected function buildClass() {

        return view('brackets/admin-generator::model', [
            'modelBaseName' => $this->modelBaseName,
            'modelNameSpace' => $this->modelNamespace,

            'dates' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "datetime" || $column['type'] == "date";
            })->pluck('name'),
            'fillable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !in_array($column['name'], ['id', 'created_at', 'updated_at', 'deleted_at']);
            })->pluck('name'),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
        ];
    }

}