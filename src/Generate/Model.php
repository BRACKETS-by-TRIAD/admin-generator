<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Schema;
use Illuminate\Support\Str;

class Model extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a model class';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $this->info($this->buildClass($this->argument('table_name')));

        $this->info('Generating a model finished');

    }

    protected function buildClass($tableName) {

        $modelName = Str::studly(Str::singular($tableName));

        return view('brackets/admin-generator::model', [
            'modelName' => $modelName,
            'namespace' => 'App\Models',
            'dates' => $this->readColumnsFromTable($tableName)->filter(function($column) {
                return true;
            })->pluck('name'),
        ])->render();
    }

    protected function getArguments() {
        return [
            ['table_name', InputArgument::REQUIRED, 'Name of the existing table'],
        ];
    }

    protected function readColumnsFromTable($tableName) {
        return collect(Schema::getColumnListing($tableName))->map(function($columnName) use ($tableName) {
            return [
                'name' => $columnName,
                'type' => Schema::getColumnType($tableName, $columnName),
                // 'required' => TODO,
            ];
        });
    }

}