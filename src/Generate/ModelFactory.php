<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModelFactory extends Generator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a factory';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        $tableName = $this->argument('table_name');
        $modelName = class_basename($this->option('model')) ?: Str::studly(Str::singular($tableName));
        $objectName = Str::snake($modelName);
        $path = base_path('database/factories/ModelFactory.php');

        $this->files->append($path, $this->buildClass($tableName, $modelName, $objectName));

        $this->info('Generating Model Factory finished');

    }

    protected function buildClass($tableName, $modelName, $objectName) {

        return view('brackets/admin-generator::factory', [
            'modelFullName' => $modelName,
            'columns' => $this->readColumnsFromTable($tableName)
                // we skip primary key
                ->filter(function($col){
                    return $col['name'] != 'id';
                })
                ->map(function($col) {
                if ($col['type'] == 'date') {
                    $type = '$faker->date()';
                } elseif ($col['type'] == 'time') {
                    $type = '$faker->time()';
                } elseif ($col['type'] == 'datetime') {
                    $type = '$faker->dateTime';
                } elseif ($col['type'] == 'text') {
                    $type = '$faker->text()';
                } elseif ($col['type'] == 'boolean') {
                    $type = '$faker->boolean()';
                } elseif ($col['type'] == 'integer' || $col['type'] == 'numeric' || $col['type'] == 'decimal') {
                    $type = '$faker->randomNumber(5)';
                } elseif ($col['type'] == 'float') {
                    $type = '$faker->randomFloat';
                } elseif ($col['name'] == 'title') {
                    $type = '$faker->sentence';
                } elseif ($col['name'] == 'name' || $col['name'] == 'first_name') {
                    $type = '$faker->firstName';
                } elseif ($col['name'] == 'surname' || $col['name'] == 'last_name') {
                    $type = '$faker->lastName';
                } elseif ($col['name'] == 'slug') {
                    $type = '$faker->unique()->slug';
                } elseif ($col['name'] == 'password') {
                    $type = 'bcrypt($faker->password)';
                } else {
                    $type = '$faker->words';
                }
                return [
                    'name' => $col['name'],
                    'faker' => $type,
                ];
            }),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
        ];
    }

}