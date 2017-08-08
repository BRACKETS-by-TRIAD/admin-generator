<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCrudGeneratorWithCustomControllerNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_controller_name_can_be_namespaced(){
        $filePath = base_path('App/Http/Controllers/Admin/Auth/UsersController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--controller-name' => 'Auth\\UsersController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use Brackets\Admin\AdminListing;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminAuth\Facades\Activation;
use Spatie\Permission\Models\Role;

class UsersController extends Controller', File::get($filePath));
    }

    /** @test */
    function user_controller_name_can_be_outside_default_directory(){
        $filePath = base_path('App/Http/Controllers/Auth/UsersController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--controller-name' => 'App\\Http\\Controllers\\Auth\\UsersController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use Brackets\Admin\AdminListing;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminAuth\Facades\Activation;
use Spatie\Permission\Models\Role;

class UsersController extends Controller', File::get($filePath));
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
Route::get(\'/admin/billing/categ-ory\',                      \'Admin\Billing\CategOryController@index\');
Route::get(\'/admin/billing/categ-ory/create\',               \'Admin\Billing\CategOryController@create\');
Route::post(\'/admin/billing/categ-ory/store\',               \'Admin\Billing\CategOryController@store\');
Route::get(\'/admin/billing/categ-ory/edit/{categOry}\',      \'Admin\Billing\CategOryController@edit\')->name(\'admin/billing/categ-ory/edit\');
Route::post(\'/admin/billing/categ-ory/update/{categOry}\',   \'Admin\Billing\CategOryController@update\')->name(\'admin/billing/categ-ory/update\');
Route::delete(\'/admin/billing/categ-ory/destroy/{categOry}\',\'Admin\Billing\CategOryController@destroy\')->name(\'admin/billing/categ-ory/destroy\');', File::get($filePath));
    }

}
