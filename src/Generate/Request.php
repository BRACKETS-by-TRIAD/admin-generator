<?php namespace Brackets\AdminGenerator\Generate;

use Symfony\Component\Console\Input\InputOption;

class Request extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a controller class';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $fileName = 'App\Http\Requests\Admin\Save'.$this->modelBaseName.'Request';

        $path = base_path($this->getPathFromClassName($fileName));

        if ($this->alreadyExists($path)) {
            $this->error('File '.$path.' already exists!');
            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass());

        $this->info('Generating '.$fileName.' finished');

    }

    protected function buildClass() {

        return view('brackets/admin-generator::request', [
            'modelBaseName' => $this->modelBaseName,

            //TODO change to better check
            'userGeneration' => $this->tableName == 'users',

            // validation in store/update
            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generates a controller for the given model'],
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
        ];
    }

}