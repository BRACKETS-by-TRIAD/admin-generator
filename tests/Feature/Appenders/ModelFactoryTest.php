<?php

namespace Brackets\AdminGenerator\Tests\Feature\Appenders;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModelFactoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function factory_generator_should_auto_generate_everything_from_table(){
        $filePath = base_path('database/factories/ModelFactory.php');

        $this->artisan('admin:generate:factory', [
            'table_name' => 'categories'
        ]);

        $this->assertStringStartsWith('<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Category::class', File::get($filePath));
    }

    /** @test */
    function you_can_specify_a_model_name(){
        $filePath = base_path('database/factories/ModelFactory.php');

        $this->artisan('admin:generate:factory', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\Cat',
        ]);

        $this->assertStringStartsWith('<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Billing\Cat::class', File::get($filePath));
    }


    /** @test */
    function you_can_specify_a_model_name_outside_default_folder(){
        $filePath = base_path('database/factories/ModelFactory.php');

        $this->artisan('admin:generate:factory', [
            'table_name' => 'categories',
            '--model-name' => 'App\\Billing\\MyCat',
        ]);

        $this->assertStringStartsWith('<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Billing\MyCat::class', File::get($filePath));
    }

}
