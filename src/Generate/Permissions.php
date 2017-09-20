<?php namespace Brackets\AdminGenerator\Generate;

use Symfony\Component\Console\Input\InputOption;

class Permissions extends ClassGenerator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions migration';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');

        if ($this->generateClass($force)){
            $this->info('Generating permissions for '.$this->modelBaseName.' finished');
        }
    }

    protected function generateClass($force = false) {
        $fileName = 'fill_permissions_for_'.$this->modelRouteAndViewName.'.php';
        $path = database_path('migrations/'.date('Y_m_d_His', time()).'_'.$fileName);

        if ($oldPath = $this->alreadyExists($fileName)) {
            $path = $oldPath;
            if($force) {
                $this->warn('File '.$path.' already exists! File will be deleted.');
                $this->files->delete($path);
            } else {
                $this->error('File '.$path.' already exists!');
                return false;
            }
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass());
        return true;
    }

    /**
     * Determine if the file already exists.
     *
     * @param $path
     * @return bool
     */
    protected function alreadyExists($path)
    {
        foreach ($this->files->files(database_path('migrations')) as $file) {
            if(str_contains($file->getFilename(), $path)) {
                return $file->getPathname();
            }
        }
        return false;
    }

    protected function buildClass() {

        return view('brackets/admin-generator::permissions', [
            'modelBaseName' => $this->modelBaseName,
            'modelDotNotation' => $this->modelDotNotation,
            'className' => $this->generateClassNameFromTable($this->tableName),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating request'],
        ];
    }

    public function generateClassNameFromTable($tableName) {
        return 'FillPermissionsFor'.$this->modelBaseName;
    }
}