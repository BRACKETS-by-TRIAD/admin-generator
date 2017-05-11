<?php namespace Brackets\AdminGenerator;

use Illuminate\Console\Command;

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

        $this->call('admin:generate:model');

        $this->info('Generating whole Admin finished');

    }

}