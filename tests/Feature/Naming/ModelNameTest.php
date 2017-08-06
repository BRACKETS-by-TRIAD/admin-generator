<?php

namespace Brackets\AdminGenerator\Tests\Feature\Naming;

use Artisan;
use Brackets\AdminGenerator\Generate\Model;
use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModelNameTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function testing_correct_name_for_standard_naming(){
        $this->assertFileNotExists(base_path('App/Models/Category.php'));

        $this->artisan('admin:generate:model', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists(base_path('App/Models/Category.php'));
    }

    /** @test */
    function testing_correct_name_for_namespaced_naming(){
        $this->assertFileNotExists(base_path('App/Models/Billing/Category.php'));

        $this->artisan('admin:generate:model', [
            'table_name' => 'categories',
            'class_name' => 'Billing\\Category',
        ]);

        $this->assertFileExists(base_path('App/Models/Billing/Category.php'));
    }

    /** @test */
    function testing_correct_name_for_name_outside_App_Models_folder(){
        $this->assertFileNotExists(base_path('App/Billing/Category.php'));

        $this->artisan('admin:generate:model', [
            'table_name' => 'categories',
            'class_name' => 'App\\Billing\\Category',
        ]);

        // FIXME
        $this->assertFileExists(base_path('App/Billing/Category.php'));
    }

}
