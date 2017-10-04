<?php

namespace Brackets\AdminGenerator\Tests\Feature\Classes;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ModelNameTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function model_name_should_auto_generate_from_table_name(){
        $filePath = base_path('app/Models/Category.php');

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
    function you_can_pass_custom_class_name_for_the_model(){
        $filePath = base_path('app/Models/Billing/Category.php');

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
    function class_name_can_be_outside_default_folder(){
        $filePath = base_path('app/Billing/Category.php');

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
