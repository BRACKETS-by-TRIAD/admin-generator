<?php

namespace Brackets\AdminGenerator\Tests\Feature\Classes;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateRequestNameTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function update_request_generation_should_generate_an_update_request_name(){
        $filePath = base_path('app/Http/Requests/Admin/Category/UpdateCategory.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:request:update', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCategory extends FormRequest', File::get($filePath));
    }

    /** @test */
    function testing_correct_name_for_custom_model_name(){
        $filePath = base_path('app/Http/Requests/Admin/Billing/Cat/UpdateCat.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:request:update', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\Cat',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\Billing\Cat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCat extends FormRequest', File::get($filePath));
    }

}
