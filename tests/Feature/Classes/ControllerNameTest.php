<?php

namespace Brackets\AdminGenerator\Tests\Feature\Classes;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ControllerNameTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function controller_should_be_generated_underdefault_namespace(){
        $filePath = base_path('app/Http/Controllers/Admin/CategoriesController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:controller', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\Category\IndexCategory;
use App\Http\Requests\Admin\Category\StoreCategory;
use App\Http\Requests\Admin\Category\UpdateCategory;
use App\Http\Requests\Admin\Category\DestroyCategory;
use Brackets\AdminListing\Facades\AdminListing;
use App\Models\Category;

class CategoriesController extends Controller', File::get($filePath));
    }

    /** @test */
    function controller_name_can_be_namespaced(){
        $filePath = base_path('app/Http/Controllers/Admin/Billing/MyNameController.php');

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
use App\Http\Requests\Admin\Category\IndexCategory;
use App\Http\Requests\Admin\Category\StoreCategory;
use App\Http\Requests\Admin\Category\UpdateCategory;
use App\Http\Requests\Admin\Category\DestroyCategory;
use Brackets\AdminListing\Facades\AdminListing;
use App\Models\Category;

class MyNameController extends Controller', File::get($filePath));
    }

    /** @test */
    function you_can_generate_controller_outside_default_directory(){
        $filePath = base_path('app/Http/Controllers/Billing/CategoriesController.php');

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
use App\Http\Requests\Admin\Category\IndexCategory;
use App\Http\Requests\Admin\Category\StoreCategory;
use App\Http\Requests\Admin\Category\UpdateCategory;
use App\Http\Requests\Admin\Category\DestroyCategory;
use Brackets\AdminListing\Facades\AdminListing;
use App\Models\Category;

class CategoriesController extends Controller', File::get($filePath));
    }


    /** @test */
    function you_can_pass_a_model_class_name(){
        $filePath = base_path('app/Http/Controllers/Billing/CategoriesController.php');

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
use App\Http\Requests\Admin\Cat\IndexCat;
use App\Http\Requests\Admin\Cat\StoreCat;
use App\Http\Requests\Admin\Cat\UpdateCat;
use App\Http\Requests\Admin\Cat\DestroyCat;
use Brackets\AdminListing\Facades\AdminListing;
use App\Billing\Cat;

class CategoriesController extends Controller', File::get($filePath));
    }

}
