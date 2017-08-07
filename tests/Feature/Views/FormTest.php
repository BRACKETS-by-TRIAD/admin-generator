<?php

namespace Brackets\AdminGenerator\Tests\Feature\Views;

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
        $formJsPath = resource_path('assets/js/admin/category/Form.js');

        $this->assertFileNotExists($elementsPath);
        $this->assertFileNotExists($createPath);
        $this->assertFileNotExists($editPath);
        $this->assertFileNotExists($formJsPath);

        $this->artisan('admin:generate:form', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($editPath));
        $this->assertStringStartsWith('var base = require(\'../components/Form/Form\');

Vue.component(\'category-form\', {
    mixins: [base]
});', File::get($formJsPath));
    }


    /** @test */
    function view_form_should_get_generated_with_custom_model(){
        $elementsPath = resource_path('views/admin/billing/my-article/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/billing/my-article/create.blade.php');
        $editPath = resource_path('views/admin/billing/my-article/edit.blade.php');
        $formJsPath = resource_path('assets/js/admin/billing-my-article/Form.js');

        $this->assertFileNotExists($elementsPath);
        $this->assertFileNotExists($createPath);
        $this->assertFileNotExists($editPath);
        $this->assertFileNotExists($formJsPath);

        $this->artisan('admin:generate:form', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\MyArticle'
        ]);

        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($editPath));
        $this->assertStringStartsWith('var base = require(\'../components/Form/Form\');

Vue.component(\'billing-my-article-form\', {
    mixins: [base]
});', File::get($formJsPath));
    }

}
