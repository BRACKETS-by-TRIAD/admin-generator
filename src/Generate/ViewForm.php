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
        if(!empty($belongsToMany = $this->option('belongs-to-many'))) {
            $this->setBelongToManyRelation($belongsToMany);
        }

        $viewPath = resource_path('views/admin/'.$this->modelViewsDirectory.'/components/form-elements.blade.php');
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildForm());

            $this->info('Generating '.$viewPath.' finished');
        }

        $viewPath = resource_path('views/admin/'.$this->modelViewsDirectory.'/create.blade.php');
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildCreate());

            $this->info('Generating '.$viewPath.' finished');
        }


        $viewPath = resource_path('views/admin/'.$this->modelViewsDirectory.'/edit.blade.php');
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildEdit());

            $this->info('Generating '.$viewPath.' finished');
        }

        $formJsPath = resource_path('assets/js/admin/'.$this->modelViewsDirectory.'/Form.js');
        $bootstrapJsPath = resource_path('assets/js/admin/bootstrap.js');

        if ($this->alreadyExists($formJsPath)) {
            $this->error('File '.$formJsPath.' already exists!');
        } else {
            $this->makeDirectory($formJsPath);

            $this->files->put($formJsPath, $this->buildFormJs());
            $this->info('Generating '.$formJsPath.' finished');

            if ($this->appendIfNotAlreadyAppended($bootstrapJsPath, "\nrequire('./".$this->modelViewsDirectory."/Form')\n")){
                $this->info('Appending Form to '.$bootstrapJsPath.' finished');
            };
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
            'modelVariableName' => $this->modelVariableName,
            'modelPlural' => $this->modelPlural,
            'modelViewsDirectory' => $this->modelViewsDirectory,
            'modelDotNotation' => $this->modelDotNotation,

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
        ])->render();
    }

    protected function buildEdit() {

        return view('brackets/admin-generator::edit', [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelVariableName' => $this->modelVariableName,
            'modelPlural' => $this->modelPlural,
            'modelViewsDirectory' => $this->modelViewsDirectory,
            'modelDotNotation' => $this->modelDotNotation,

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
        ])->render();
    }

    protected function buildFormJs() {
        return view('brackets/admin-generator::form-js', [
            'modelViewsDirectory' => $this->modelViewsDirectory,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['belongs-to-many', 'btm', InputOption::VALUE_OPTIONAL, 'Specify belongs to many relations'],
        ];
    }

}