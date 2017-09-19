<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Routes extends FileAppender {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append admin routes into a web routes file';

    /**
     * Path for view
     *
     * @var string
     */
    protected $view = 'routes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //TODO check if exists
        //TODO make global for all generator
        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.routes';
        }

        if ($this->appendIfNotAlreadyAppended(base_path('routes/web.php'), "\n\n".$this->buildClass())){
            $this->info('Appending routes finished');
        }
    }

    protected function buildClass() {

        return view('brackets/admin-generator::'.$this->view, [
            'controllerPartiallyFullName' => $this->controllerWithNamespaceFromDefault,
            'modelVariableName' => $this->modelVariableName,
            'modelViewsDirectory' => $this->modelViewsDirectory,
            'resource' => $this->resource,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a controller for the given model'],
            ['controller-name', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
        ];
    }

}