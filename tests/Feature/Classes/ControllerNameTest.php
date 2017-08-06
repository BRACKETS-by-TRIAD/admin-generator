<?php

namespace Brackets\AdminGenerator\Tests\Feature\Classes;

use Brackets\AdminGenerator\Tests\TestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ControllerNameTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function controller_should_be_generated_underdefault_namespace(){
        $filePath = base_path('App/Http/Controllers/Admin/CategoriesController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:controller', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\StoreCategory;
use App\Http\Requests\Admin\UpdateCategory;
use Brackets\Admin\AdminListing;
use App\Models\Category;

class CategoriesController extends Controller', File::get($filePath));
    }

    /** @test */
    function controller_name_can_be_namespaced(){
        $filePath = base_path('App/Http/Controllers/Admin/Billing/MyNameController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:controller', [
            'table_name' => 'categories',
            'class_name' => 'Billing\\MyNameController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\StoreCategory;
use App\Http\Requests\Admin\UpdateCategory;
use Brackets\Admin\AdminListing;
use App\Models\Category;

class MyNameController extends Controller', File::get($filePath));
    }

    /** @test */
    function you_can_generate_controller_outside_default_directory(){
        $filePath = base_path('App/Http/Controllers/Billing/CategoriesController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:controller', [
            'table_name' => 'categories',
            'class_name' => 'App\\Http\\Controllers\\Billing\\CategoriesController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\StoreCategory;
use App\Http\Requests\Admin\UpdateCategory;
use Brackets\Admin\AdminListing;
use App\Models\Category;

class CategoriesController extends Controller', File::get($filePath));
    }


    /** @test */
    function you_can_pass_a_model_class_name(){
        $filePath = base_path('App/Http/Controllers/Billing/CategoriesController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:controller', [
            'table_name' => 'categories',
            'class_name' => 'App\\Http\\Controllers\\Billing\\CategoriesController',
            '--model-name' => 'App\\Billing\\Cat',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\StoreCat;
use App\Http\Requests\Admin\UpdateCat;
use Brackets\Admin\AdminListing;
use App\Billing\Cat;

class CategoriesController extends Controller', File::get($filePath));
    }

}
