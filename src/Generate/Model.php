<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;

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
        $modelName = Str::studly(Str::singular($tableName));
        $modelFullName = $this->qualifyClass("Models\\".$modelName);
        $modelPath = $path = $this->getPath($modelFullName);

        if ($this->alreadyExists($modelPath)) {
            $this->error('File '.$modelPath.' already exists!');
            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($tableName, $modelName));

        // TODO think if we should use ide-helper:models ?

        $this->info('Generating model '.$modelName.' finished');

    }

    protected function buildClass($tableName, $modelName) {

        return view('brackets/admin-generator::model', [
            'modelName' => $modelName,
            'namespace' => 'App\Models',
            'dates' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return $column['type'] == "datetime" || $column['type'] == "date";
            })->pluck('name'),
            'fillable' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return !in_array($column['name'], ['id', 'created_at', 'updated_at']);
            })->pluck('name'),
        ])->render();
    }

}