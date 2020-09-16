<?php

namespace Brackets\AdminGenerator\Tests\Feature\Views;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class FormTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function view_form_should_get_auto_generated(): void
    {
        $elementsPath = resource_path('views/admin/category/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/category/create.blade.php');
        $editPath = resource_path('views/admin/category/edit.blade.php');
        $formJsPath = resource_path('js/admin/category/Form.js');
        $indexJsPath = resource_path('js/admin/category/index.js');
        $bootstrapJsPath = resource_path('js/admin/index.js');

        $this->assertFileDoesNotExist($elementsPath);
        $this->assertFileDoesNotExist($createPath);
        $this->assertFileDoesNotExist($editPath);
        $this->assertFileDoesNotExist($formJsPath);
        $this->assertFileDoesNotExist($indexJsPath);
        $this->assertFileDoesNotExist($bootstrapJsPath);


        $this->artisan('admin:generate:form', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);
        $this->assertFileExists($indexJsPath);
        $this->assertFileExists($bootstrapJsPath);
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($editPath));
        $this->assertStringStartsWith('import AppForm from \'../app-components/Form/AppForm\';

Vue.component(\'category-form\', {
    mixins: [AppForm]', File::get($formJsPath));
        $this->assertStringStartsWith('import \'./Form\'', File::get($indexJsPath));
        $this->assertStringStartsWith('import \'./category\';', File::get($bootstrapJsPath));
    }

    /** @test */
    public function view_form_should_get_generated_with_custom_model(): void
    {
        $elementsPath = resource_path('views/admin/billing/my-article/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/billing/my-article/create.blade.php');
        $editPath = resource_path('views/admin/billing/my-article/edit.blade.php');
        $formJsPath = resource_path('js/admin/billing-my-article/Form.js');
        $indexJsPath = resource_path('js/admin/billing-my-article/index.js');
        $bootstrapJsPath = resource_path('js/admin/index.js');

        $this->assertFileDoesNotExist($elementsPath);
        $this->assertFileDoesNotExist($createPath);
        $this->assertFileDoesNotExist($editPath);
        $this->assertFileDoesNotExist($formJsPath);
        $this->assertFileDoesNotExist($indexJsPath);
        $this->assertFileDoesNotExist($bootstrapJsPath);

        $this->artisan('admin:generate:form', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\MyArticle'
        ]);

        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);
        $this->assertFileExists($indexJsPath);
        $this->assertFileExists($bootstrapJsPath);
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($editPath));
        $this->assertStringStartsWith('import AppForm from \'../app-components/Form/AppForm\';

Vue.component(\'billing-my-article-form\', {
    mixins: [AppForm]', File::get($formJsPath));
        $this->assertStringStartsWith('import \'./Form\'', File::get($indexJsPath));
        $this->assertStringStartsWith('import \'./billing-my-article\';', File::get($bootstrapJsPath));
    }
}
