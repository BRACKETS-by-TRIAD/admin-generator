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
    Route::get(\'/admin/categories\',                             \'Admin\CategoriesController@index\');
    Route::get(\'/admin/categories/create\',                      \'Admin\CategoriesController@create\');
    Route::post(\'/admin/categories\',                            \'Admin\CategoriesController@store\');
    Route::get(\'/admin/categories/{category}/edit\',             \'Admin\CategoriesController@edit\')->name(\'admin/categories/edit\');
    Route::post(\'/admin/categories/{category}\',                 \'Admin\CategoriesController@update\')->name(\'admin/categories/update\');
    Route::delete(\'/admin/categories/{category}\',               \'Admin\CategoriesController@destroy\')->name(\'admin/categories/destroy\');', File::get($filePath));
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
    Route::get(\'/admin/billing-categ-ories\',                    \'Admin\Billing\CategOryController@index\');
    Route::get(\'/admin/billing-categ-ories/create\',             \'Admin\Billing\CategOryController@create\');
    Route::post(\'/admin/billing-categ-ories\',                   \'Admin\Billing\CategOryController@store\');
    Route::get(\'/admin/billing-categ-ories/{categOry}/edit\',    \'Admin\Billing\CategOryController@edit\')->name(\'admin/billing-categ-ories/edit\');
    Route::post(\'/admin/billing-categ-ories/{categOry}\',        \'Admin\Billing\CategOryController@update\')->name(\'admin/billing-categ-ories/update\');
    Route::delete(\'/admin/billing-categ-ories/{categOry}\',      \'Admin\Billing\CategOryController@destroy\')->name(\'admin/billing-categ-ories/destroy\');', File::get($filePath));
    }

}
