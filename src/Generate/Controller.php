<?php namespace Brackets\AdminGenerator\Generate;

use Brackets\AdminGenerator\Generate\Traits\FileManipulations;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Controller extends ClassGenerator {

    use FileManipulations;

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
     * Path for view
     *
     * @var string
     */
    protected $view = 'controller';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');

        // TODO test the case, if someone passes a class_name outside Laravel's default App\Http\Controllers folder, if it's going to work

        //TODO check if exists
        //TODO make global for all generator
        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.controller';
        }

        if(!empty($belongsToMany = $this->option('belongs-to-many'))) {
            $this->setBelongToManyRelation($belongsToMany);
        }

        if ($this->generateClass($force)){

            $this->info('Generating '.$this->classFullName.' finished');

            $icon = array_random(['icon-graduation', 'icon-puzzle', 'icon-compass', 'icon-drop', 'icon-globe', 'icon-ghost', 'icon-book-open', 'icon-flag', 'icon-star', 'icon-umbrella', 'icon-energy', 'icon-plane', 'icon-magnet', 'icon-diamond']);
            if ($this->strReplaceInFile(
                resource_path('views/admin/layout/sidebar.blade.php'),
                '|url\(\'admin\/'.$this->resource.'\'\)|',
                "{{-- Do not delete me :) I'm used for auto-generation menu items --}}",
                "<li class=\"nav-item\"><a class=\"nav-link\" href=\"{{ url('admin/".$this->resource."') }}\"><i class=\"".$icon."\"></i> <span class=\"nav-link-text\">{{ trans('admin.".$this->modelLangFormat.".title') }}</span></a></li>\n            {{-- Do not delete me :) I'm used for auto-generation menu items --}}"
            )) {
                $this->info('Updating sidebar');
            }
        }

    }

    protected function buildClass() {

        return view('brackets/admin-generator::'.$this->view, [
            'controllerBaseName' => $this->classBaseName,
            'controllerNamespace' => $this->classNamespace,
            'modelBaseName' => $this->modelBaseName,
            'modelFullName' => $this->modelFullName,
            'modelPlural' => $this->modelPlural,
            'modelVariableName' => $this->modelVariableName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelViewsDirectory' => $this->modelViewsDirectory,
            'modelDotNotation' => $this->modelDotNotation,
            'modelWithNamespaceFromDefault' => $this->modelWithNamespaceFromDefault,
            'resource' => $this->resource,

            // index
            'columnsToQuery' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "remember_token" || $column['name'] == "slug" || $column['name'] == "created_at" || $column['name'] == "updated_at" || $column['name'] == "deleted_at");
            })->pluck('name')->toArray(),
            'columnsToSearchIn' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return ($column['type'] == 'json' || $column['type'] == 'text' || $column['type'] == 'string' || $column['name'] == "id") && !($column['name'] == "password" || $column['name'] == "remember_token");
            })->pluck('name')->toArray(),
            //            'filters' => $this->readColumnsFromTable($tableName)->filter(function($column) {
            //                return $column['type'] == 'boolean' || $column['type'] == 'date';
            //            }),
            // validation in store/update
            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
            'relations' => $this->relations,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['belongs-to-many', 'btm', InputOption::VALUE_OPTIONAL, 'Specify belongs to many relations'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating controller'],
        ];
    }

    public function generateClassNameFromTable($tableName) {
        return Str::studly($tableName).'Controller';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers\Admin';
    }
}