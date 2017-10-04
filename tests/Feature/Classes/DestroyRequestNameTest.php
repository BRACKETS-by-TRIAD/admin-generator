<?php

namespace Brackets\AdminGenerator\Tests\Feature\Classes;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DestroyRequestNameTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function destroy_request_generation_should_generate_an_update_request_name(){
        $filePath = base_path('app/Http/Requests/Admin/Category/DestroyCategory.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:request:destroy', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DestroyCategory extends FormRequest', File::get($filePath));
    }

    /** @test */
    function testing_correct_name_for_custom_model_name_in_destroy_request(){
        $filePath = base_path('app/Http/Requests/Admin/Billing/Cat/DestroyCat.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:request:destroy', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\Cat',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\Billing\Cat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DestroyCat extends FormRequest', File::get($filePath));
    }

}
