<?php

namespace Brackets\AdminGenerator\Tests\Feature\Appenders;

use Brackets\AdminGenerator\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class RoutesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function auto_generated_routes_append(): void
    {
        $filePath = base_path('routes/web.php');

        $this->artisan('admin:generate:routes', [
            'table_name' => 'categories'
        ]);

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::prefix(\'admin\')->namespace(\'App\Http\Controllers\Admin\')->name(\'admin/\')->group(static function() {
        Route::prefix(\'categories\')->name(\'categories/\')->group(static function() {
            Route::get(\'/\',                                             \'CategoriesController@index\')->name(\'index\');
            Route::get(\'/create\',                                       \'CategoriesController@create\')->name(\'create\');
            Route::post(\'/\',                                            \'CategoriesController@store\')->name(\'store\');
            Route::get(\'/{category}/edit\',                              \'CategoriesController@edit\')->name(\'edit\');
            Route::post(\'/bulk-destroy\',                                \'CategoriesController@bulkDestroy\')->name(\'bulk-destroy\');
            Route::post(\'/{category}\',                                  \'CategoriesController@update\')->name(\'update\');
            Route::delete(\'/{category}\',                                \'CategoriesController@destroy\')->name(\'destroy\');
        });
    });
});',
            File::get($filePath));
    }

    /** @test */
    public function custom_model_and_controller_name(): void
    {
        $filePath = base_path('routes/web.php');

        $this->artisan('admin:generate:routes', [
            'table_name' => 'categories',
            '--model-name' => 'Billing\\CategOry',
            '--controller-name' => 'Billing\\CategOryController',
        ]);

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::prefix(\'admin\')->namespace(\'App\Http\Controllers\Admin\')->name(\'admin/\')->group(static function() {
        Route::prefix(\'billing-categ-ories\')->name(\'billing-categ-ories/\')->group(static function() {
            Route::get(\'/\',                                             \'Billing\CategOryController@index\')->name(\'index\');
            Route::get(\'/create\',                                       \'Billing\CategOryController@create\')->name(\'create\');
            Route::post(\'/\',                                            \'Billing\CategOryController@store\')->name(\'store\');
            Route::get(\'/{categOry}/edit\',                              \'Billing\CategOryController@edit\')->name(\'edit\');
            Route::post(\'/bulk-destroy\',                                \'Billing\CategOryController@bulkDestroy\')->name(\'bulk-destroy\');
            Route::post(\'/{categOry}\',                                  \'Billing\CategOryController@update\')->name(\'update\');
            Route::delete(\'/{categOry}\',                                \'Billing\CategOryController@destroy\')->name(\'destroy\');
        });
    });
});',
            File::get($filePath));
    }
}
