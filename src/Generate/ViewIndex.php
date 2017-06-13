<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ViewIndex extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an index view template';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $tableName = $this->argument('table_name');
        $modelName = class_basename($this->option('model')) ?: Str::studly(Str::singular($tableName));
        $objectName = Str::snake($modelName);
        $viewPath = $this->getPath('views/admin/'.$objectName.'/index');

        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
            return false;
        }

        $this->makeDirectory($viewPath);

        $this->files->put($viewPath, $this->buildClass($tableName, $modelName, $objectName));

        $this->info('Generating '.$viewPath.' finished');

    }

    protected function getPath($path)
    {
        return resource_path($path.'.blade.php');
    }

    protected function buildClass($tableName, $modelName, $objectName) {

        return view('brackets/admin-generator::index', [
            'modelName' => $modelName,
            'objectName' => $objectName,
            'objectNamePlural' => Str::plural($objectName),
            'columns' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "slug" || $column['name'] == "created_at" || $column['name'] == "updated_at");
            })->pluck('name'),
            'filters' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return $column['type'] == 'boolean' || $column['type'] == 'date';
            }),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
        ];
    }

}