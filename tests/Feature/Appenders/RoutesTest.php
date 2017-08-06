<?php

namespace Brackets\AdminGenerator\Tests\Feature\Appenders;

use Brackets\AdminGenerator\Tests\TestCase;
use File;
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
Route::get(\'/admin/category\',                               \'Admin\CategoriesController@index\');
Route::get(\'/admin/category/create\',                        \'Admin\CategoriesController@create\');
Route::post(\'/admin/category/store\',                        \'Admin\CategoriesController@store\');
Route::get(\'/admin/category/edit/{category}\',               \'Admin\CategoriesController@edit\')->name(\'admin/category/edit\');
Route::post(\'/admin/category/update/{category}\',            \'Admin\CategoriesController@update\')->name(\'admin/category/update\');
Route::delete(\'/admin/category/destroy/{category}\',         \'Admin\CategoriesController@destroy\')->name(\'admin/category/destroy\');', File::get($filePath));
    }


    /** @test */
    function custom_model_and_controller_name(){
        $filePath = base_path('routes/web.php');

        $this->artisan('admin:generate:routes', [
            'table_name' => 'categories',
            '--model-name' => 'App\\Billing\\CategOry',
            '--controller-name' => 'Billing\\CategOryController',
        ]);

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::get(\'/admin/categ-ory\',                              \'Admin\Billing\CategOryController@index\');
Route::get(\'/admin/categ-ory/create\',                       \'Admin\Billing\CategOryController@create\');
Route::post(\'/admin/categ-ory/store\',                       \'Admin\Billing\CategOryController@store\');
Route::get(\'/admin/categ-ory/edit/{categOry}\',              \'Admin\Billing\CategOryController@edit\')->name(\'admin/categ-ory/edit\');
Route::post(\'/admin/categ-ory/update/{categOry}\',           \'Admin\Billing\CategOryController@update\')->name(\'admin/categ-ory/update\');
Route::delete(\'/admin/categ-ory/destroy/{categOry}\',        \'Admin\Billing\CategOryController@destroy\')->name(\'admin/categ-ory/destroy\');', File::get($filePath));
    }

}
