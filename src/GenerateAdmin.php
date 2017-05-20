<?php namespace Brackets\AdminGenerator;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class GenerateAdmin extends Command {

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

        $tableName = $this->argument('table_name');

        $modelFullName = $this->option('model') ?: "App\\Models\\".Str::studly(Str::singular($tableName));
        $modelName = class_basename($modelFullName);

        $this->call('admin:generate:model', [
            'table_name' => $tableName,
            '--model' => $modelName,
        ]);

        $this->call('admin:generate:controller', [
            'table_name' => $tableName,
            '--model' => $modelName,
            '--controller' => $this->option('controller'),
        ]);

        $this->call('admin:generate:index', [
            'table_name' => $tableName,
            '--model' => $modelName,
        ]);

        $this->call('admin:generate:create', [
            'table_name' => $tableName,
            '--model' => $modelName,
        ]);

        $this->call('admin:generate:edit', [
            'table_name' => $tableName,
            '--model' => $modelName,
        ]);

        $this->call('admin:generate:factory', [
            'table_name' => $tableName,
            '--model' => $modelName,
        ]);

        if ($this->option('seed')) {
            $this->info('Seeding testing data');
            factory($modelFullName, 20)->create();
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