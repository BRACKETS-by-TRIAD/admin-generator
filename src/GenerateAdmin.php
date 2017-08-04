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

        $this->call('admin:generate:request:store', [
            'table_name' => $tableNameArgument,
            '--model' => $modelOption,
            '--controller' => $controllerOption,
        ]);

        $this->call('admin:generate:request:update', [
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

        $this->call('admin:generate:form', [
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


/**
 *
 * - napis skelet tried
 * - napis test, pre scenare: Category, App\Category, Billing\Category a skontrolovat vsetky ClassGenerator-y
 *
 * ClassGenerator: (For every class_name, if it does start with "App\" then no namespace is applied. Else "(..)" is applied)
 *
 * Admin: seed, controller_name, model_name (mozno neskor priame prefix)
 *
 * Model: class_name (App\Models), template, belongs_to_many
 *
 * Controller: class_name (App\Http\Controllers\Admin), model_name, template, belongs_to_many
 *
 * StoreRequest: class_name (App\Http\Requests\Admin\{model_name})
 *
 * UpdateRequest: class_name (App\Http\Requests\Admin\{model_name}), model_name
 *
 * DestroyRequest: class_name (App\Http\Requests\Admin\{model_name}), model_name
 *
 *
 * Appendor:
 *
 * ModelFactory: model_name
 *
 * Routes: model_name, controller_name, template
 *
 *
 * ViewGenerator:
 *
 * ViewForm: file_name, model_name, belongs_to_many
 *
 * ViewFullForm: file_name, model_name, template, name, view_name, route
 *
 * ViewIndex: file_name, model_name, template
 *
 *
 */
