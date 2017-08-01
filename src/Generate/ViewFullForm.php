<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ViewFullForm extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:full-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate form view template';

    /**
     * Path for view
     *
     * @var string
     */
    protected $view = 'full-form';

    /**
     * Path for js view
     *
     * @var string
     */
    protected $viewJs = 'form-js';

    /**
     * Name of view, will be used in directory
     *
     * @var string
     */
    protected $viewName;

    /**
     * Name of generated form
     *
     * @var string
     */
    protected $formName = 'form';

    /**
     * Route to process form
     *
     * @var string
     */
    protected $route;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        //TODO check if exists
        //TODO make global for all generator
        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.full-form';
            $this->viewJs = 'templates.'.$template.'.form-js';
        }

        if(!empty($formName = $this->option('name'))) {
            $this->formName = $formName;
        }

        if(!empty($viewName = $this->option('view-name'))) {
            $this->viewName = $viewName;
        } else {
            $this->viewName = $this->modelRouteAndViewName;
        }

        if(!empty($route = $this->option('route'))) {
            $this->route = $route;
        } else {
            $this->route = 'admin/'.$this->viewName.'/update';
        }

        $viewPath = resource_path('views/admin/'.$this->viewName.'/'.$this->formName.'.blade.php');
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
            return false;
        }
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildForm());

            $this->info('Generating '.$viewPath.' finished');
        }

        $formJsPath = resource_path('assets/js/admin/'.$this->viewName.'/Form.js');
        $bootstrapJsPath = resource_path('assets/js/admin/bootstrap.js');

        if ($this->alreadyExists($formJsPath)) {
            $this->error('File '.$formJsPath.' already exists!');
        } else {
            $this->makeDirectory($formJsPath);

            $this->files->put($formJsPath, $this->buildFormJs());

            $this->appendIfNotAlreadyAppended($bootstrapJsPath, "require('./".$this->viewName."/Form')\n");

            $this->info('Generating '.$formJsPath.' finished');
        }

    }

    protected function buildForm() {

        return view('brackets/admin-generator::'.$this->view, [
            'modelBaseName' => $this->modelBaseName,
            'viewName' => $this->viewName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelPlural' => $this->modelPlural,
            'route' => $this->route,

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
        ])->render();
    }

    protected function buildFormJs() {
        return view('brackets/admin-generator::'.$this->viewJs, [
            'viewName' => $this->viewName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['name', 'nm', InputOption::VALUE_OPTIONAL, 'Specify custom form name'],
            ['view-name', 'vn', InputOption::VALUE_OPTIONAL, 'Specify custom name for view'],
            ['route', 'r', InputOption::VALUE_OPTIONAL, 'Specify custom route for form'],
        ];
    }

}