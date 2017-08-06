<?php

namespace Brackets\AdminGenerator\Tests\Feature\Classes;

use Brackets\AdminGenerator\Tests\TestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FormTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function view_form_should_get_auto_generated(){
        $elementsPath = resource_path('views/admin/category/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/category/create.blade.php');
        $editPath = resource_path('views/admin/category/edit.blade.php');

        $this->assertFileNotExists($elementsPath);
        $this->assertFileNotExists($createPath);
        $this->assertFileNotExists($editPath);

        $this->artisan('admin:generate:form', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($editPath));
    }


    /** @test */
    function view_form_should_get_generated_with_custom_model(){
        $elementsPath = resource_path('views/admin/article/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/article/create.blade.php');
        $editPath = resource_path('views/admin/article/edit.blade.php');

        $this->assertFileNotExists($elementsPath);
        $this->assertFileNotExists($createPath);
        $this->assertFileNotExists($editPath);

        $this->artisan('admin:generate:form', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\Article'
        ]);

        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($editPath));
    }

}
