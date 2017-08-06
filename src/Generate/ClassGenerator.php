<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ClassGenerator extends Command {

    public $tableName;
    public $classBaseName;
    public $classPartialName;
    public $classFullName;
    public $classNamespace;
    public $modelBaseName;
    public $modelFullName;
    public $modelPlural;
    public $modelVariableName;
    public $modelRouteAndViewName;
    public $modelNamespace;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * Relations
     *
     * @var string
     */
    protected $relations = [];

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    protected function getArguments() {
        return [
            ['table_name', InputArgument::REQUIRED, 'Name of the existing table'],
            ['class_name', InputArgument::OPTIONAL, 'Name of the generated class'],
        ];
    }

    /**
     * Generate default class name (for case if not passed as argument) from table name
     *
     * @param $tableName
     * @return mixed
     */
    abstract protected function generateClassNameFromTable($tableName);

    /**
     * Build the class with the given name.
     *
     * @return string
     */
    abstract protected function buildClass();

    /**
     * @param $tableName
     * @return Collection
     */
    protected function readColumnsFromTable($tableName) {

        // TODO how to process jsonb & json translatable columns? need to figure it out

        $indexes = collect(Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes($tableName));
        return collect(Schema::getColumnListing($tableName))->map(function($columnName) use ($tableName, $indexes) {

            //Checked unique index
            $columnUniqueIndexes = $indexes->filter(function($index) use ($columnName) {
                return in_array($columnName, $index->getColumns()) && ($index->isUnique() && !$index->isPrimary());
            });
            $columnUniqueDeleteAtCondition = $columnUniqueIndexes->filter(function($index) {
                return $index->hasOption('where') ? $index->getOption('where') == '(deleted_at IS NULL)' : false;
            });

            // TODO add foreign key

            return [
                'name' => $columnName,
                'type' => Schema::getColumnType($tableName, $columnName),
                'required' => boolval(Schema::getConnection()->getDoctrineColumn($tableName, $columnName)->getNotnull()),
                'unique' => $columnUniqueIndexes->count() > 0,
                'unique_deleted_at_condition' => $columnUniqueDeleteAtCondition->count() > 0,
            ];
        });
    }

    protected function getVisibleColumns($tableName, $modelVariableName) {
        $columns = $this->readColumnsFromTable($tableName);
        $hasSoftDelete = ($columns->filter(function($column) {
            return $column['name'] == "deleted_at";
        })->count() > 0);
        return $columns->filter(function($column) {
            return !($column['name'] == "id" || $column['name'] == "created_at" || $column['name'] == "updated_at" || $column['name'] == "deleted_at" || $column['name'] == "remember_token");
        })->map(function($column) use ($tableName, $hasSoftDelete, $modelVariableName){
            $serverStoreRules = collect([]);
            $serverUpdateRules = collect([]);
            $frontendRules = collect([]);
            if ($column['required']) {
                $serverStoreRules->push('required');
                $serverUpdateRules->push('required');
                if($column['type'] != 'boolean' && $column['name'] != 'password') {
                    $frontendRules->push('required');
                }
            } else {
                $serverStoreRules->push('nullable');
                $serverUpdateRules->push('nullable');
            }

            if ($column['name'] == 'email') {
                $serverStoreRules->push('email');
                $serverUpdateRules->push('email');
                $frontendRules->push('email');
            }

            if ($column['name'] == 'password') {
                $serverStoreRules->push('confirmed');
                $serverUpdateRules->push('sometimes');
                $serverUpdateRules->push('confirmed');
                $frontendRules->push('confirmed:password_confirmation');

                $serverStoreRules->push('min:7');
                $serverUpdateRules->push('min:7');
                $frontendRules->push('min:7');

                $serverStoreRules->push('regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/');
                $serverUpdateRules->push('regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/');
                //TODO not working, need fixing
//                $frontendRules->push('regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!$#%]).*$/g');
            }

            if ($column['unique']) {
                $storeRule = 'unique:'.$tableName;
                $updateRule = 'unique:'.$tableName.','.$column['name'].',\'.$'.$modelVariableName.'->getKey().\',\'.$'.$modelVariableName.'->getKeyName().\'';
                if($hasSoftDelete && $column['unique_deleted_at_condition']) {
                    $storeRule .= ','.$column['name'].',NULL,id,deleted_at,NULL';
                    $updateRule .= ',deleted_at,NULL';
                }
                $serverStoreRules->push($storeRule);
                $serverUpdateRules->push($updateRule);
            }

            switch ($column['type']) {
                case 'datetime':
                    $serverStoreRules->push('date');
                    $serverUpdateRules->push('date');
                    $frontendRules->push('date_format:YYYY-MM-DD kk:mm:ss');
                    break;
                case 'date':
                    $serverStoreRules->push('date');
                    $serverUpdateRules->push('date');
                    $frontendRules->push('date_format:YYYY-MM-DD kk:mm:ss');
                    break;
                case 'time':
                    $serverStoreRules->push('date_format:H:i:s');
                    $serverUpdateRules->push('date_format:H:i:s');
                    $frontendRules->push('date_format:kk:mm:ss');
                    break;
                case 'integer':
                    $serverStoreRules->push('integer');
                    $serverUpdateRules->push('integer');
                    $frontendRules->push('numeric');
                    break;
                case 'boolean':
                    $serverStoreRules->push('boolean');
                    $serverUpdateRules->push('boolean');
                    $frontendRules->push('');
                    break;
                case 'float':
                    $serverStoreRules->push('numeric');
                    $serverUpdateRules->push('numeric');
                    $frontendRules->push('decimal');
                    break;
                case 'decimal':
                    $serverStoreRules->push('numeric');
                    $serverUpdateRules->push('numeric');
                    $frontendRules->push('decimal'); // FIXME?? I'm not sure about this one
                    break;
                case 'string':
                    $serverStoreRules->push('string');
                    $serverUpdateRules->push('string');
                    break;
                case 'text':
                    $serverStoreRules->push('string');
                    $serverUpdateRules->push('string');
                    break;
                default:
                    $serverStoreRules->push('string');
                    $serverUpdateRules->push('string');
            }

            return [
                'name' => $column['name'],
                'type' => $column['type'],
                'serverStoreRules' => $serverStoreRules->toArray(),
                'serverUpdateRules' => $serverUpdateRules->toArray(),
                'frontendRules' => $frontendRules->toArray(),
            ];
        });
    }

    /**
     * Determine if the file already exists.
     *
     * @param $path
     * @return bool
     */
    protected function alreadyExists($path)
    {
        return $this->files->exists($path);
    }


    /**
     * Determine if the content is already present in the file
     *
     * @param $path
     * @param $content
     * @return bool
     */
    protected function alreadyAppended($path, $content)
    {
        if (strpos($this->files->get($path), $content) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Append content to file only if if the content is not present in the file
     *
     * @param $path
     * @param $content
     */
    protected function appendIfNotAlreadyAppended($path, $content)
    {
        if (!$this->alreadyAppended($path, $content)) {
            $this->files->append($path, $content);
        }
    }

    public function option($key = null) {
        return ($key === null || $this->hasOption($key)) ? parent::option($key) : null;
    }


    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    public function getPathFromClassName($name) {
        return str_replace('\\', '/', $name).".php";
    }

    /**
     * Check if provided relation has a table
     *
     * @param $relationTable
     * @return mixed
     */
    public function checkRelationTable($relationTable)
    {
        return Schema::hasTable($relationTable);
    }

    /**
     * sets Relation of Belongs To Many type
     *
     * @param $belongsToMany
     * @return mixed
     */
    //TODO add other relation types
    public function setBelongToManyRelation($belongsToMany)
    {
        $this->relations['belongsToMany'] = collect(explode(',', $belongsToMany))->filter(function($belongToManyRelation) {
            return $this->checkRelationTable($belongToManyRelation);
        })->map(function($belongsToMany) {
            return [
                'current_table' => $this->tableName,
                'related_table' => $belongsToMany,
                'related_model' => ($belongsToMany == 'roles') ? "Spatie\\Permission\\Models\\Role" : "App\\Models\\". Str::studly(Str::singular($belongsToMany)),
                'related_model_class' => ($belongsToMany == 'roles') ? "Spatie\\Permission\\Models\\Role::class" : "App\\Models\\". Str::studly(Str::singular($belongsToMany)).'::class',
                'related_model_name' => Str::studly(Str::singular($belongsToMany)),
                'related_model_name_plural' => Str::studly($belongsToMany),
                'related_model_variable_name' => lcfirst(Str::singular(class_basename($belongsToMany))),
                'relation_table' => trim(collect([$this->tableName, $belongsToMany])->sortBy(function($table) {
                    return $table;
                })->reduce(function($relationTable, $table) {
                    return $relationTable.'_'.$table;
                }), '_'),
                'foreign_key' => Str::singular($this->tableName).'_id',
                'related_key' => Str::singular($belongsToMany).'_id',
            ];
        })->keyBy('related_table');
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    protected function generateClass() {
        $path = base_path($this->getPathFromClassName($this->classFullName));

        if ($this->alreadyExists($path)) {
            $this->error('File '.$path.' already exists!');
            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass());
    }

    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initNames($this->argument('table_name'), $this->argument('class_name'), $this->option('model-name'));

        $output = parent::execute($input, $output);

        $this->info('Generating '.$this->classBaseName.' finished');

        return $output;
    }

    protected function initNames($tableName, $className = null, $modelName = null) {
        $this->tableName = $tableName;

        if ($this instanceof Model) {
            $this->initModelName($className);
        } else {
            $this->initModelName(is_null($modelName) ? Str::studly(Str::singular($this->tableName)) : $modelName);
        }

        if (empty($className)) {
            $className = $this->generateClassNameFromTable($this->tableName);
        }

        $this->classFullName = $this->qualifyClass($className);
        $this->classBaseName = class_basename($this->classFullName);
        $this->classNamespace = Str::replaceLast("\\".$this->classBaseName, '', $this->classFullName);
    }

    protected function initModelName($modelName) {
        if ($this instanceof Model) {
            $this->modelFullName = $this->qualifyClass($modelName);
        } else {
            $modelGenerator = app(Model::class);
            $modelGenerator->setLaravel($this->laravel);
            $this->modelFullName = $modelGenerator->qualifyClass($modelName);
        }
        $this->modelBaseName = class_basename($modelName);
        $this->modelPlural = Str::plural(class_basename($modelName));
        $this->modelVariableName = lcfirst(Str::singular(class_basename($this->modelBaseName)));
        $this->modelRouteAndViewName = Str::lower(Str::kebab($this->modelBaseName));
        $this->modelNamespace = Str::replaceLast("\\" . $this->modelBaseName, '', $this->modelFullName);
    }

}