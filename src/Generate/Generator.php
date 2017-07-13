<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;
use Schema;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Generator extends Command {

    protected $tableName;

    protected $controllerBaseName;
    protected $controllerPartiallyFullName;
    protected $controllerFullName;
    protected $controllerNamespace;

    protected $modelBaseName;
    protected $modelFullName;
    protected $modelPlural;
    protected $modelVariableName;
    protected $modelRouteAndViewName;
    protected $modelNamespace;

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
        ];
    }

    /**
     * @param $tableName
     * @return Collection
     */
    protected function readColumnsFromTable($tableName) {

        // TODO process also "_translation" table when using i18n models

        $indexes = collect(Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes($tableName));
        return collect(Schema::getColumnListing($tableName))->map(function($columnName) use ($tableName, $indexes) {

            //Checked unique index
            $columnUniqueIndexes = $indexes->filter(function($index) use ($columnName) {
                return in_array($columnName, $index->getColumns()) && ($index->isUnique() && !$index->isPrimary());
            });
            $columnUniqueDeleteAtCondition = $columnUniqueIndexes->filter(function($index) {
                return $this->hasOption('where') ? $index->getOption('where') == '(deleted_at IS NULL)' : false;
            });

            //TODO add foreign key

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
                    $frontendRules->push('date_format:YYYY-MM-DD');
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
     * Determine if the class already exists.
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
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initNames();
        return parent::execute($input, $output);
    }

    protected function initNames() {
        $this->tableName = $this->argument('table_name');

        $this->controllerBaseName = class_basename($this->option('controller')) ?: (Str::studly($this->tableName) . "Controller");
        $this->controllerPartiallyFullName = "Admin\\".($this->option('controller') ?: (Str::studly($this->tableName) . "Controller"));
        $this->controllerFullName = "App\\Http\\Controllers\\".$this->controllerPartiallyFullName;
        $this->controllerNamespace = Str::replaceLast("\\".$this->controllerBaseName, '', $this->controllerFullName);

        $this->modelBaseName = class_basename($this->option('model')) ?: Str::studly(Str::singular($this->tableName));
        $this->modelFullName = "App\\Models\\".($this->option('model') ?: Str::studly(Str::singular($this->tableName)));
        $this->modelPlural = Str::plural(class_basename($this->option('model'))) ?: Str::studly($this->tableName);
        $this->modelVariableName = lcfirst(Str::singular(class_basename($this->modelBaseName)));
        $this->modelRouteAndViewName = Str::lower(Str::kebab($this->modelBaseName));
        $this->modelNamespace = Str::replaceLast("\\".$this->modelBaseName, '', $this->modelFullName);
    }
}