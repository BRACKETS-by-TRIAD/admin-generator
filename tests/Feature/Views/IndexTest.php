<?php

namespace Brackets\AdminGenerator\Tests\Feature\Views;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function index_listing_should_get_auto_generated(): void
    {
        $indexPath = resource_path('views/admin/category/index.blade.php');
        $listingJsPath = resource_path('js/admin/category/Listing.js');
        $indexJsPath = resource_path('js/admin/category/index.js');
        $bootstrapJsPath = resource_path('js/admin/index.js');

        $this->assertFileDoesNotExist($indexPath);
        $this->assertFileDoesNotExist($listingJsPath);
        $this->assertFileDoesNotExist($indexJsPath);

        $this->artisan('admin:generate:index', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($indexPath);
        $this->assertFileExists($listingJsPath);
        $this->assertFileExists($indexJsPath);
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($indexPath));
        $this->assertStringStartsWith('import AppListing from \'../app-components/Listing/AppListing\';

Vue.component(\'category-listing\', {
    mixins: [AppListing]
});', File::get($listingJsPath));
        $this->assertStringStartsWith('import \'./Listing\'', File::get($indexJsPath));
        $this->assertStringStartsWith('import \'./category\';', File::get($bootstrapJsPath));
    }

    /** @test */
    public function index_listing_should_get_auto_generated_with_custom_model(): void
    {
        $indexPath = resource_path('views/admin/billing/my-article/index.blade.php');
        $listingJsPath = resource_path('js/admin/billing-my-article/Listing.js');
        $indexJsPath = resource_path('js/admin/billing-my-article/index.js');
        $bootstrapJsPath = resource_path('js/admin/index.js');

        $this->assertFileDoesNotExist($indexPath);
        $this->assertFileDoesNotExist($listingJsPath);
        $this->assertFileDoesNotExist($indexJsPath);


        $this->artisan('admin:generate:index', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\MyArticle'
        ]);

        $this->assertFileExists($indexPath);
        $this->assertFileExists($listingJsPath);
        $this->assertFileExists($indexJsPath);
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($indexPath));
        $this->assertStringStartsWith('import AppListing from \'../app-components/Listing/AppListing\';

Vue.component(\'billing-my-article-listing\', {
    mixins: [AppListing]
});', File::get($listingJsPath));

        $this->assertStringStartsWith('import \'./Listing\';', File::get($indexJsPath));
        $this->assertStringStartsWith('import \'./billing-my-article\';', File::get($bootstrapJsPath));
    }
}
