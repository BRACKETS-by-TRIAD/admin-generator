<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModelFactory extends FileAppender {

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
    protected $description = 'Append a new factory';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        if ($this->appendIfNotAlreadyAppended(base_path('database/factories/ModelFactory.php'), $this->buildClass())){
            $this->info('Appending '.$this->modelBaseName.' model to ModelFactory finished');
        }
    }

    protected function buildClass() {

        return view('brackets/admin-generator::factory', [
            'modelFullName' => $this->modelFullName,

            'columns' => $this->readColumnsFromTable($this->tableName)
                // we skip primary key
                ->filter(function($col){
                    return $col['name'] != 'id';
                })
                ->map(function($col) {
                if($col['name'] == 'deleted_at') {
                    $type = 'null';
                } else if($col['name'] == 'remember_token') {
                    $type = 'null';
                } else {
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
                    } elseif ($col['name'] == 'email') {
                        $type = '$faker->email';
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
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
        ];
    }

}