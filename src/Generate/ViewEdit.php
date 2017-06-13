<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ViewEdit extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:edit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an edit view template';

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
        $viewPath = $this->getPath('views/admin/'.$objectName.'/edit');

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

        $cols = $this->readColumnsFromTable($tableName);

        return view('brackets/admin-generator::edit', [
            'modelName' => $modelName,
            'objectName' => $objectName,
            'objectNamePlural' => Str::plural($objectName),
            'titleColumn' => $cols->pluck('name')->contains('title') ? 'title' : ($cols->contains('name') ? 'name' : 'id'),
            'columns' => $cols->filter(function($column) {
                return !($column['name'] == "id" || $column['name'] == "created_at" || $column['name'] == "updated_at" || $column['name'] == "deleted_at");
            }),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
        ];
    }

}