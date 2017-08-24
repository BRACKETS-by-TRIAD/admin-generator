<?php

namespace Brackets\AdminGenerator\Tests\Feature\Views;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function index_listing_should_get_auto_generated(){
        $indexPath = resource_path('views/admin/category/index.blade.php');
        $indexJsPath = resource_path('assets/admin/js/category/Listing.js');

        $this->assertFileNotExists($indexPath);
        $this->assertFileNotExists($indexJsPath);

        $this->artisan('admin:generate:index', [
            'table_name' => 'categories'
        ]);

        $this->assertFileExists($indexPath);
        $this->assertFileExists($indexJsPath);
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.default\')', File::get($indexPath));
        $this->assertStringStartsWith('import AppListing from \'../components/Listing/AppListing\';

Vue.component(\'category-listing\', {
    mixins: [AppListing]
});', File::get($indexJsPath));
    }


    /** @test */
    function index_listing_should_get_auto_generated_with_custom_model(){
        $indexPath = resource_path('views/admin/billing/my-article/index.blade.php');
        $indexJsPath = resource_path('assets/admin/js/billing-my-article/Listing.js');

        $this->assertFileNotExists($indexPath);
        $this->assertFileNotExists($indexJsPath);

        $this->artisan('admin:generate:index', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\MyArticle'
        ]);

        $this->assertFileExists($indexPath);
        $this->assertFileExists($indexJsPath);
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.default\')', File::get($indexPath));
        $this->assertStringStartsWith('import AppListing from \'../components/Listing/AppListing\';

Vue.component(\'billing-my-article-listing\', {
    mixins: [AppListing]
});', File::get($indexJsPath));
    }

}
