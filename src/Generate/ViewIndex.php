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
        $viewPath = resource_path('views/admin/'.$objectName.'/index.blade.php');
        $listingJsPath = resource_path('assets/js/admin/'.$objectName.'/Listing.js');
        $bootstrapJsPath = resource_path('assets/js/admin/bootstrap.js');

        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildClass($tableName, $modelName, $objectName));

            $this->info('Generating '.$viewPath.' finished');
        }

        if ($this->alreadyExists($listingJsPath)) {
            $this->error('File '.$listingJsPath.' already exists!');
        } else {
            $this->makeDirectory($listingJsPath);

            $this->files->put($listingJsPath, $this->buildListingJs($objectName));

            $this->files->append($bootstrapJsPath, "\nrequire('./".$objectName."/Listing')");

            $this->info('Generating '.$listingJsPath.' finished');
        }

    }

    protected function buildClass($tableName, $modelName, $objectName) {

        return view('brackets/admin-generator::index', [
            'modelName' => $modelName,
            'objectName' => $objectName,
            'objectNamePlural' => Str::plural($objectName),
            'columns' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "slug" || $column['name'] == "created_at" || $column['name'] == "updated_at" || $column['name'] == "deleted_at");
            })->pluck('name'),
//            'filters' => $this->readColumnsFromTable($tableName)->filter(function($column) {
//                return $column['type'] == 'boolean' || $column['type'] == 'date';
//            }),
        ])->render();
    }

    protected function buildListingJs($objectName) {
        return view('brackets/admin-generator::listing-js', [
            'objectName' => $objectName,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
        ];
    }

}