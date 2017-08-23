<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Lang extends FileAppender {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:lang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append admin translations into a admin lang file';

    /**
     * Path for view
     *
     * @var string
     */
    protected $view = 'lang';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
//        //TODO check if exists
//        //TODO make global for all generator
//        //TODO also with prefix
//        if(!empty($template = $this->option('template'))) {
//            $this->view = 'templates.'.$template.'.lang';
//        }

        if(empty($locale = $this->option('locale'))) {
            $locale = 'en';
        }

        if ($this->appendIfNotAlreadyAppended(resource_path('lang/'.$locale.'/admin.php'), "\n\n".$this->buildClass())){
            $this->info('Appending translations finished');
        }
    }

    protected function buildClass() {

        return view('brackets/admin-generator::'.$this->view, [
            'modelBaseName' => $this->modelBaseName,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a controller for the given model'],
            ['locale', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom locale'],
//            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
        ];
    }

}