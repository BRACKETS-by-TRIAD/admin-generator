<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ViewForm extends ViewGenerator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate create and edit view templates';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if(!empty($belongsToMany = $this->option('belongsToMany'))) {
            $this->setBelongToManyRelation($belongsToMany);
        }

        $viewPath = resource_path('views/admin/'.$this->modelRouteAndViewName.'/components/form-elements.blade.php');
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildForm());

            $this->info('Generating '.$viewPath.' finished');
        }

        $viewPath = resource_path('views/admin/'.$this->modelRouteAndViewName.'/create.blade.php');
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildCreate());

            $this->info('Generating '.$viewPath.' finished');
        }


        $viewPath = resource_path('views/admin/'.$this->modelRouteAndViewName.'/edit.blade.php');
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildEdit());

            $this->info('Generating '.$viewPath.' finished');
        }

        $formJsPath = resource_path('assets/js/admin/'.$this->modelRouteAndViewName.'/Form.js');
        $bootstrapJsPath = resource_path('assets/js/admin/bootstrap.js');

        if ($this->alreadyExists($formJsPath)) {
            $this->error('File '.$formJsPath.' already exists!');
        } else {
            $this->makeDirectory($formJsPath);

            $this->files->put($formJsPath, $this->buildFormJs());

            $this->appendIfNotAlreadyAppended($bootstrapJsPath, "\nrequire('./".$this->modelRouteAndViewName."/Form')\n");

            $this->info('Generating '.$formJsPath.' finished');
        }

    }

    protected function buildForm() {

        return view('brackets/admin-generator::form', [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelPlural' => $this->modelPlural,

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
            'relations' => $this->relations,
        ])->render();
    }

    protected function buildCreate() {

        return view('brackets/admin-generator::create', [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelPlural' => $this->modelPlural,

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
        ])->render();
    }

    protected function buildEdit() {

        return view('brackets/admin-generator::edit', [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelPlural' => $this->modelPlural,

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
        ])->render();
    }

    protected function buildFormJs() {
        return view('brackets/admin-generator::form-js', [
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['belongs-to-many', 'btm', InputOption::VALUE_OPTIONAL, 'Specify belongs to many relations'],
        ];
    }

}