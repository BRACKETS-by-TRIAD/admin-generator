<?php namespace Brackets\AdminGenerator;

use Brackets\AdminGenerator\Generate\Generator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateAdmin extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold complete CRUD admin interface';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $tableNameArgument = $this->argument('table_name');
        $modelOption = $this->option('model');
        $controllerOption = $this->option('controller');

        $this->call('admin:generate:model', [
            'table_name' => $tableNameArgument,
            '--model' => $modelOption,
        ]);

        $this->call('admin:generate:controller', [
            'table_name' => $tableNameArgument,
            '--model' => $modelOption,
            '--controller' => $controllerOption,
        ]);


        $this->call('admin:generate:routes', [
            'table_name' => $tableNameArgument,
            '--model' => $modelOption,
            '--controller' => $controllerOption,
        ]);

        $this->call('admin:generate:index', [
            'table_name' => $tableNameArgument,
            '--model' => $modelOption,
        ]);

        $this->call('admin:generate:create', [
            'table_name' => $tableNameArgument,
            '--model' => $modelOption,
        ]);

        $this->call('admin:generate:edit', [
            'table_name' => $tableNameArgument,
            '--model' => $modelOption,
        ]);

        $this->call('admin:generate:factory', [
            'table_name' => $tableNameArgument,
            '--model' => $modelOption,
        ]);

        if ($this->option('seed')) {
            $this->info('Seeding testing data');
            factory($this->modelFullName, 20)->create();
        }

        $this->info('Generating whole admin finished');

    }

    protected function getArguments() {
        return [
            ['table_name', InputArgument::REQUIRED, 'Name of the existing table'],
        ];
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
            ['seed', 's', InputOption::VALUE_NONE, 'Seeds table with fake data'],
        ];
    }

}