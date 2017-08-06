<?php

namespace Brackets\AdminGenerator\Tests\Feature\Naming;

use Brackets\AdminGenerator\Tests\TestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateRequestNameTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function update_request_generation_should_generate_an_update_request_name(){
        $filePath = base_path('App/Http/Requests/Admin/Category/UpdateCategory.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:request:update', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateCategory extends FormRequest', File::get($filePath));
    }

    /** @test */
    function testing_correct_name_for_custom_model_name(){
        $filePath = base_path('App/Http/Requests/Admin/Cat/UpdateCat.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:request:update', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\Cat',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\Cat;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateCat extends FormRequest', File::get($filePath));
    }

}
