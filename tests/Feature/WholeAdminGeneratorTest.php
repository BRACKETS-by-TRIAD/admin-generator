<?php

namespace Brackets\AdminGenerator\Tests\Feature\Classes;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WholeAdminGeneratorTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function whole_admin_generator_produces_all_the_files(){
        $modelPath = base_path('app/Models/Category.php');
        $controllerPath = base_path('app/Http/Controllers/Admin/CategoriesController.php');
        $indexRequestPath = base_path('app/Http/Requests/Admin/Category/IndexCategory.php');
        $storePath = base_path('app/Http/Requests/Admin/Category/StoreCategory.php');
        $updatePath = base_path('app/Http/Requests/Admin/Category/UpdateCategory.php');
        $destroyPath = base_path('app/Http/Requests/Admin/Category/DestroyCategory.php');
        $routesPath = base_path('routes/web.php');
        $indexPath = resource_path('views/admin/category/index.blade.php');
        $listingJsPath = resource_path('assets/admin/js/category/Listing.js');
        $elementsPath = resource_path('views/admin/category/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/category/create.blade.php');
        $editPath = resource_path('views/admin/category/edit.blade.php');
        $formJsPath = resource_path('assets/admin/js/category/Form.js');
        $factoryPath = base_path('database/factories/ModelFactory.php');
		$indexJsPath = resource_path('assets/admin/js/category/index.js');
		$langPath = resource_path('lang/en/admin.php');

        $this->assertFileNotExists($controllerPath);
        $this->assertFileNotExists($indexRequestPath);
        $this->assertFileNotExists($storePath);
        $this->assertFileNotExists($updatePath);
        $this->assertFileNotExists($destroyPath);
        $this->assertFileNotExists($indexPath);
        $this->assertFileNotExists($listingJsPath);
        $this->assertFileNotExists($elementsPath);
        $this->assertFileNotExists($createPath);
        $this->assertFileNotExists($editPath);
        $this->assertFileNotExists($formJsPath);
        $this->assertFileNotExists($modelPath);
        $this->assertFileNotExists($routesPath);
        $this->assertFileNotExists($factoryPath);
		$this->assertFileNotExists($indexJsPath);
		$this->assertFileNotExists($langPath);

        $this->artisan('admin:generate', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($controllerPath);
        $this->assertFileExists($indexRequestPath);
        $this->assertFileExists($storePath);
        $this->assertFileExists($updatePath);
        $this->assertFileExists($destroyPath);
        $this->assertFileExists($indexPath);
        $this->assertFileExists($listingJsPath);
        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);
        $this->assertFileExists($modelPath);
        $this->assertFileExists($routesPath);
        $this->assertFileExists($factoryPath);
		$this->assertFileExists($indexJsPath);
		$this->assertFileExists($langPath);
    }

}
