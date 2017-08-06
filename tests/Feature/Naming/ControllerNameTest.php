<?php

namespace Brackets\AdminGenerator\Tests\Feature\Naming;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ControllerNameTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function testing_correct_name_for_standard_naming(){
        $filePath = 'App/Http/Controllers/Admin/CategoriesController.php';

        $this->assertFileNotExists(base_path($filePath));

        $this->artisan('admin:generate:controller', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists(base_path($filePath));
    }

    /** @test */
    function testing_correct_name_for_namespaced_naming(){
        $filePath = 'App/Http/Controllers/Admin/Billing/MyNameController.php';

        $this->assertFileNotExists(base_path($filePath));

        $this->artisan('admin:generate:controller', [
            'table_name' => 'categories',
            'class_name' => 'Billing\\MyNameController',
        ]);

        $this->assertFileExists(base_path($filePath));
    }

    /** @test */
    function testing_correct_name_for_name_outside_default_folder(){
        $filePath = 'App/Http/Controllers/Billing/CategoriesController.php';

        $this->assertFileNotExists(base_path($filePath));

        $this->artisan('admin:generate:controller', [
            'table_name' => 'categories',
            'class_name' => 'App\\Http\\Controllers\\Billing\\CategoriesController',
        ]);

        $this->assertFileExists(base_path($filePath));
    }

}
