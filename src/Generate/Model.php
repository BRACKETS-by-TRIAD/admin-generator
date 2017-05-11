<?php namespace Brackets\AdminGenerator\Generate;

use Illuminate\Console\Command;

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

        $this->info('Generating a model finished');

    }

}