<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Model extends ClassGenerator {

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
     * Path for view
     *
     * @var string
     */
    protected $view = 'model';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');

        //TODO check if exists
        //TODO make global for all generator
        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.model';
        }

        if(!empty($belongsToMany = $this->option('belongs-to-many'))) {
            $this->setBelongToManyRelation($belongsToMany);
        }

        if ($this->generateClass($force)){
            $this->info('Generating '.$this->classFullName.' finished');
        }

        // TODO think if we should use ide-helper:models ?
    }

    protected function buildClass() {
        return view('brackets/admin-generator::'.$this->view, [
            'modelBaseName' => $this->classBaseName,
            'modelNameSpace' => $this->classNamespace,

            // if table name differs from the snake case plural form of the classname, then we need to specify the table name
            'tableName' => ($this->tableName !== Str::snake(Str::plural($this->classBaseName))) ? $this->tableName : null,

            'dates' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "datetime" || $column['type'] == "date";
            })->pluck('name'),
            'fillable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !in_array($column['name'], ['id', 'created_at', 'updated_at', 'deleted_at', 'remember_token']);
            })->pluck('name'),
            'hidden' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return in_array($column['name'], ['password', 'remember_token']);
            })->pluck('name'),
            'translatable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "json";
            })->pluck('name'),
            'timestamps' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return in_array($column['name'], ['created_at', 'updated_at']);
            })->count() > 0,
            'hasSoftDelete' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['name'] == "deleted_at";
            })->count() > 0,
            'resource' => $this->resource,

            'relations' => $this->relations,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['belongs-to-many', 'btm', InputOption::VALUE_OPTIONAL, 'Specify belongs to many relations'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating model'],
        ];
    }

    public function generateClassNameFromTable($tableName) {
        return Str::studly(Str::singular($tableName));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Models';
    }
}