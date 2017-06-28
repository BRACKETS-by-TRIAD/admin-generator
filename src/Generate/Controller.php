<?php namespace Brackets\AdminGenerator\Generate;

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
        $controllerPath = base_path($this->getPathFromClassName($this->controllerFullName));

        if ($this->alreadyExists($controllerPath)) {
            $this->error('File '.$controllerPath.' already exists!');
            return false;
        }

        $this->makeDirectory($controllerPath);

        $this->files->put($controllerPath, $this->buildClass());

        $sidebarPath = resource_path('views/admin/layout/sidebar.blade.php');
        $this->files->put($sidebarPath, str_replace("{{-- Do not delete me :) I'm used for auto-generation menu items --}}", "<a class=\"nav-link\" href=\"{{ url('admin/".$this->modelRouteAndViewName."') }}\"><i class=\"icon-list\"></i> ".$this->modelPlural."</a>\n                {{-- Do not delete me :) I'm used for auto-generation menu items --}}", $this->files->get($sidebarPath)));

        $this->info('Generating '.$this->controllerBaseName.' finished');

    }

    protected function buildClass() {

        return view('brackets/admin-generator::controller', [
            'controllerBaseName' => $this->controllerBaseName,
            'controllerNamespace' => $this->controllerNamespace,
            'modelBaseName' => $this->modelBaseName,
            'modelFullName' => $this->modelFullName,
            'modelPlural' => $this->modelPlural,
            'modelVariableName' => $this->modelVariableName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,

            // index
            'columnsToQuery' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "slug" || $column['name'] == "created_at" || $column['name'] == "updated_at" || $column['name'] == "deleted_at");
            })->pluck('name')->toArray(),
            'columnsToSearchIn' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == 'text' || $column['type'] == 'string' || $column['name'] == "id";
            })->pluck('name')->toArray(),
//            'filters' => $this->readColumnsFromTable($tableName)->filter(function($column) {
//                return $column['type'] == 'boolean' || $column['type'] == 'date';
//            }),

            // validation in store/update
            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generates a controller for the given model'],
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
        ];
    }

}