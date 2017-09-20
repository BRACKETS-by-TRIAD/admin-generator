<?php

namespace Brackets\AdminGenerator\Tests\Feature\Appenders;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoutesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function autog_enerated_routes_append(){
        $filePath = base_path('routes/web.php');

        $this->artisan('admin:generate:routes', [
            'table_name' => 'categories'
        ]);

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'admin\'])->group(function () {
    Route::get(\'/admin/category\',                               \'Admin\CategoriesController@index\');
    Route::get(\'/admin/category/create\',                        \'Admin\CategoriesController@create\');
    Route::post(\'/admin/category\',                              \'Admin\CategoriesController@store\');
    Route::get(\'/admin/category/{category}/edit\',               \'Admin\CategoriesController@edit\')->name(\'admin/category/edit\');
    Route::post(\'/admin/category/{category}\',                   \'Admin\CategoriesController@update\')->name(\'admin/category/update\');
    Route::delete(\'/admin/category/{category}\',                 \'Admin\CategoriesController@destroy\')->name(\'admin/category/destroy\');', File::get($filePath));
    }


    /** @test */
    function custom_model_and_controller_name(){
        $filePath = base_path('routes/web.php');

        $this->artisan('admin:generate:routes', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\CategOry',
            '--controller-name' => 'Billing\\CategOryController',
        ]);

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'admin\'])->group(function () {
    Route::get(\'/admin/billing/categ-ory\',                      \'Admin\Billing\CategOryController@index\');
    Route::get(\'/admin/billing/categ-ory/create\',               \'Admin\Billing\CategOryController@create\');
    Route::post(\'/admin/billing/categ-ory\',                     \'Admin\Billing\CategOryController@store\');
    Route::get(\'/admin/billing/categ-ory/{categOry}/edit\',      \'Admin\Billing\CategOryController@edit\')->name(\'admin/billing/categ-ory/edit\');
    Route::post(\'/admin/billing/categ-ory/{categOry}\',          \'Admin\Billing\CategOryController@update\')->name(\'admin/billing/categ-ory/update\');
    Route::delete(\'/admin/billing/categ-ory/{categOry}\',        \'Admin\Billing\CategOryController@destroy\')->name(\'admin/billing/categ-ory/destroy\');', File::get($filePath));
    }

}
