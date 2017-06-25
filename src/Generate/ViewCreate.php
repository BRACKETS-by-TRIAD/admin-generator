<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ViewCreate extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a create view template';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $viewPath = resource_path('views/admin/'.$this->modelRouteAndViewName.'/create.blade.php');
//        $listingJsPath = resource_path('assets/js/admin/'.$this->modelRouteAndViewName.'/Listing.js');
//        $bootstrapJsPath = resource_path('assets/js/admin/bootstrap.js');

        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
            return false;
        }
        if ($this->alreadyExists($viewPath)) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildView());

            $this->info('Generating '.$viewPath.' finished');
        }
//
//        if ($this->alreadyExists($listingJsPath)) {
//            $this->error('File '.$listingJsPath.' already exists!');
//        } else {
//            $this->makeDirectory($listingJsPath);
//
//            $this->files->put($listingJsPath, $this->buildListingJs());
//
//            $this->files->append($bootstrapJsPath, "\nrequire('./".$this->modelRouteAndViewName."/Listing')");
//
//            $this->info('Generating '.$listingJsPath.' finished');
//        }

    }

    protected function buildView() {

        return view('brackets/admin-generator::create', [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelPlural' => $this->modelPlural,

            'columns' => $this->getVisibleColumns($this->tableName),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
        ];
    }

}