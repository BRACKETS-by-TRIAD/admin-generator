<?php namespace Brackets\AdminGenerator\Generate\Traits;

use Brackets\AdminGenerator\Generate\Controller;
use Brackets\AdminGenerator\Generate\Model;
use Illuminate\Support\Str;

trait Names {

    public $tableName;

    public $modelBaseName;
    public $modelFullName;
    public $modelPlural;
    public $modelVariableName;
    public $modelRouteAndViewName;
    public $modelNamespace;

    public $controllerNameInRoutes;

    protected function initCommonNames($tableName, $modelName = null, $controllerName = null) {
        $this->tableName = $tableName;

        if ($this instanceof Model) {
            $modelGenerator = $this;
        } else {
            $modelGenerator = app(Model::class);
            $modelGenerator->setLaravel($this->laravel);
        }

        if (is_null($modelName)) {
            $modelName = $modelGenerator->generateClassNameFromTable($this->tableName);
        }
        $this->modelFullName = $modelGenerator->qualifyClass($modelName);

        $this->modelBaseName = class_basename($modelName);
        $this->modelPlural = Str::plural(class_basename($modelName));
        $this->modelVariableName = lcfirst(Str::singular(class_basename($this->modelBaseName)));
        $this->modelRouteAndViewName = Str::lower(Str::kebab($this->modelBaseName));
        $this->modelNamespace = Str::replaceLast("\\" . $this->modelBaseName, '', $this->modelFullName);

        if ($this instanceof Controller) {
            $controllerGenerator = $this;
        } else {
            $controllerGenerator = app(Controller::class);
            $controllerGenerator->setLaravel($this->laravel);
        }

        if (is_null($controllerName)) {
            $controllerName = $controllerGenerator->generateClassNameFromTable($this->tableName);
        }

        $controllerFullName = $controllerGenerator->qualifyClass($controllerName);
        $this->controllerNameInRoutes = Str::replaceFirst($controllerGenerator->rootNamespace().'\\Http\\Controllers', '', $controllerFullName);
    }

}
