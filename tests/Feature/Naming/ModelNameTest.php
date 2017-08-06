<?php

namespace Brackets\AdminGenerator\Tests\Feature\Naming;

use Brackets\AdminGenerator\Tests\TestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModelNameTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function testing_correct_name_for_standard_naming(){
        $filePath = base_path('App/Models/Category.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:model', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model', File::get($filePath));
    }

    /** @test */
    function testing_correct_name_for_namespaced_naming(){
        $filePath = base_path('App/Models/Billing/Category.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:model', [
            'table_name' => 'categories',
            'class_name' => 'Billing\\Category',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class Category extends Model', File::get($filePath));
    }

    /** @test */
    function testing_correct_name_for_name_outside_default_folder(){
        $filePath = base_path('App/Billing/Category.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:model', [
            'table_name' => 'categories',
            'class_name' => 'App\\Billing\\Category',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Billing;

use Illuminate\Database\Eloquent\Model;

class Category extends Model', File::get($filePath));
    }

}
