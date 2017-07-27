<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ViewIndex extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an index view template';

    /**
     * Path for view
     *
     * @var string
     */
    protected $view = 'index';

    /**
     * Path for js view
     *
     * @var string
     */
    protected $viewJs = 'listing-js';

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
            $this->view = 'templates.'.$template.'.index';
            $this->viewJs = 'templates.'.$template.'.listing-js';
        }

        $viewPath = resource_path('views/admin/'.$this->modelRouteAndViewName.'/index.blade.php');
        $listingJsPath = resource_path('assets/js/admin/'.$this->modelRouteAndViewName.'/Listing.js');
        $bootstrapJsPath = resource_path('assets/js/admin/bootstrap.js');

        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildView());

            $this->info('Generating '.$viewPath.' finished');
        }

        if ($this->alreadyExists($listingJsPath)) {
            $this->error('File '.$listingJsPath.' already exists!');
        } else {
            $this->makeDirectory($listingJsPath);

            $this->files->put($listingJsPath, $this->buildListingJs());

            $this->appendIfNotAlreadyAppended($bootstrapJsPath, "require('./".$this->modelRouteAndViewName."/Listing')\n");

            $this->info('Generating '.$listingJsPath.' finished');
        }

    }

    protected function buildView() {

        return view('brackets/admin-generator::'.$this->view, [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelPlural' => $this->modelPlural,

            'columns' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "remember_token" || $column['name'] == "slug" || $column['name'] == "created_at" || $column['name'] == "updated_at" || $column['name'] == "deleted_at");
                })->map(function($col){

                    $filters = collect([]);
                    $col['switch'] = false;

                    if ($col['type'] == 'date' || $col['type'] == 'time' || $col['type'] == 'datetime') {
                        $filters->push($col['type']);
                    }

                    if ($col['name'] == 'enabled' || $col['name'] == 'activated') {
                        $col['switch'] = true;
                    }

                    $col['filters'] = $filters->isNotEmpty() ? ' | '.implode(' | ', $filters->toArray()) : '';

                    return $col;
                }),
//            'filters' => $this->readColumnsFromTable($tableName)->filter(function($column) {
//                return $column['type'] == 'boolean' || $column['type'] == 'date';
//            }),
        ])->render();
    }

    protected function buildListingJs() {
        return view('brackets/admin-generator::'.$this->viewJs, [
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
        ];
    }

}