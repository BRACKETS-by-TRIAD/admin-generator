<?php namespace Brackets\AdminGenerator\Generate\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

trait Columns {

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
                $serverStoreRules->push('\'required\'');
                $serverUpdateRules->push('\'sometimes\'');
                if($column['type'] != 'boolean' && $column['name'] != 'password') {
                    $frontendRules->push('required');
                }
            } else {
                $serverStoreRules->push('\'nullable\'');
                $serverUpdateRules->push('\'nullable\'');
            }

            if ($column['name'] == 'email') {
                $serverStoreRules->push('\'email\'');
                $serverUpdateRules->push('\'email\'');
                $frontendRules->push('email');
            }

            if ($column['name'] == 'password') {
                $serverStoreRules->push('\'confirmed\'');
                $serverUpdateRules->push('\'confirmed\'');
                $frontendRules->push('confirmed:password_confirmation');

                $serverStoreRules->push('\'min:7\'');
                $serverUpdateRules->push('\'min:7\'');
                $frontendRules->push('min:7');

                $serverStoreRules->push('\'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/\'');
                $serverUpdateRules->push('\'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/\'');
                //TODO not working, need fixing
//                $frontendRules->push(''regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!$#%]).*$/g'');
            }

            if($column['unique'] || $column['name'] == 'slug') {
                if($column['type'] == 'json') {
                    $storeRule = 'Rule::unique(\''.$tableName.'\', \''.$column['name'].'->\'.$locale)';
                    $updateRule = 'Rule::unique(\''.$tableName.'\', \''.$column['name'].'->\'.$locale)->ignore($this->'.$modelVariableName.'->getKey(), $this->'.$modelVariableName.'->getKeyName())';
                    if($hasSoftDelete && $column['unique_deleted_at_condition']) {
                        $storeRule .= '->whereNull(\'deleted_at\')';
                        $updateRule .= '->whereNull(\'deleted_at\')';
                    }
                    $serverStoreRules->push($storeRule);
                    $serverUpdateRules->push($updateRule);
                } else {
                    $storeRule = 'Rule::unique(\''.$tableName.'\', \''.$column['name'].'\')';
                    $updateRule = 'Rule::unique(\''.$tableName.'\', \''.$column['name'].'\')->ignore($this->'.$modelVariableName.'->getKey(), $this->'.$modelVariableName.'->getKeyName())';
                    if($hasSoftDelete && $column['unique_deleted_at_condition']) {
                        $storeRule .= '->whereNull(\'deleted_at\')';
                        $updateRule .= '->whereNull(\'deleted_at\')';
                    }
                    $serverStoreRules->push($storeRule);
                    $serverUpdateRules->push($updateRule);
                }
            }

            switch ($column['type']) {
                case 'datetime':
                    $serverStoreRules->push('\'date\'');
                    $serverUpdateRules->push('\'date\'');
                    $frontendRules->push('date_format:YYYY-MM-DD HH:mm:ss');
                    break;
                case 'date':
                    $serverStoreRules->push('\'date\'');
                    $serverUpdateRules->push('\'date\'');
                    $frontendRules->push('date_format:YYYY-MM-DD HH:mm:ss');
                    break;
                case 'time':
                    $serverStoreRules->push('\'date_format:H:i:s\'');
                    $serverUpdateRules->push('\'date_format:H:i:s\'');
                    $frontendRules->push('date_format:HH:mm:ss');
                    break;
                case 'integer':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('numeric');
                    break;
                case 'boolean':
                    $serverStoreRules->push('\'boolean\'');
                    $serverUpdateRules->push('\'boolean\'');
                    $frontendRules->push('');
                    break;
                case 'float':
                    $serverStoreRules->push('\'numeric\'');
                    $serverUpdateRules->push('\'numeric\'');
                    $frontendRules->push('decimal');
                    break;
                case 'decimal':
                    $serverStoreRules->push('\'numeric\'');
                    $serverUpdateRules->push('\'numeric\'');
                    $frontendRules->push('decimal'); // FIXME?? I'm not sure about this one
                    break;
                case 'string':
                    $serverStoreRules->push('\'string\'');
                    $serverUpdateRules->push('\'string\'');
                    break;
                case 'text':
                    $serverStoreRules->push('\'string\'');
                    $serverUpdateRules->push('\'string\'');
                    break;
                default:
                    $serverStoreRules->push('\'string\'');
                    $serverUpdateRules->push('\'string\'');
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

}