<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ViewFullForm extends ViewGenerator {

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
    protected $description = 'Generate a full-form view template';

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
    protected $fileName;

    /**
     * Route to process form
     *
     * @var string
     */
    protected $route;

    /**
     * @var string
     */
    protected $formJsRelativePath;

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

        $this->fileName = $this->option('file-name') ?: $this->modelViewsDirectory;
        $this->formJsRelativePath = str_replace(DIRECTORY_SEPARATOR, '-', $this->fileName);
        if (!$this->option('file-name')) {
            $this->fileName = $this->fileName . DIRECTORY_SEPARATOR . 'form';
        }

        $this->route = $this->option('route');
        if (!$this->route){
            if ($this->option('file-name')){
                $this->route = 'admin/'.$this->fileName;
            } else {
                $this->route = 'admin/'.$this->modelViewsDirectory.'/update';
            }
        }

        $viewPath = resource_path('views/admin/'.$this->fileName.'.blade.php');
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildForm());

            $this->info('Generating '.$viewPath.' finished');
        }

        $formJsPath = resource_path('assets/admin/js/'.$this->formJsRelativePath.'/Form.js');
        $bootstrapJsPath = resource_path('assets/admin/js/index.js');

        if ($this->alreadyExists($formJsPath)) {
            $this->error('File '.$formJsPath.' already exists!');
        } else {
            $this->makeDirectory($formJsPath);

            $this->files->put($formJsPath, $this->buildFormJs());
            $this->info('Generating '.$formJsPath.' finished');

            if ($this->appendIfNotAlreadyAppended($bootstrapJsPath, "require('./".$this->formJsRelativePath."/Form')\n")){
                $this->info('Appending Form to '.$bootstrapJsPath.' finished');
            }
        }

    }

    protected function buildForm() {

        return view('brackets/admin-generator::'.$this->view, [
            'modelBaseName' => $this->modelBaseName,
            'modelVariableName' => $this->modelVariableName,
            'route' => $this->route,
            'modelJSName' => $this->formJsRelativePath,

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
            'relations' => $this->relations,
        ])->render();
    }

    protected function buildFormJs() {
        return view('brackets/admin-generator::'.$this->viewJs, [
            'modelJSName' => $this->formJsRelativePath,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['file-name', 'nm', InputOption::VALUE_OPTIONAL, 'Specify a blade file path'],
            ['route', 'r', InputOption::VALUE_OPTIONAL, 'Specify custom route for form'],
        ];
    }

}